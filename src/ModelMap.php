<?php

namespace Chak\Modeldiver;

use Illuminate\Support\Facades\File;
use Schema;
use ReflectionClass;
use ReflectionMethod;
use Throwable;
use TypeError;
use Illuminate\Database\QueryException;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Database\Eloquent\Model;

class ModelMap
{
    public function getModelsNames(string $modelsPath)
    {
        return collect(File::allFiles($modelsPath))
            ->map(function ($item) {
                $path = $item->getFilename();

                $namespace = $this->extractNamespace($item->getRealPath()) . '\\';

                $class = sprintf(
                    '\%s%s',
                    $namespace,
                    strtr(substr($path, 0, strrpos($path, '.')), '/', '\\')
                );
                return $class;
            })
            ->filter(function ($class) {
                $valid = false;

                if (class_exists($class)) {
                    $reflection = new ReflectionClass($class);
                    $valid = $reflection->isSubclassOf(Model::class) && !$reflection->isAbstract();
                }

                return $valid;
            });
    }

    public function getLinkDataArray(string $modelsPath)
    {
        $linkDataArray = [];
        $modelNames = $this->getModelsNames($modelsPath);

        foreach ($modelNames as $modelName) {
            $model = app($modelName);
            $links = $this->getLinks($model);
            foreach ($links as $link) {
                $linkDataArray[] = $link;
            }
        }

        return $linkDataArray;
    }

    public function getNodeDataArray(string $modelsPath)
    {
        $nodeDataArray = [];
        $modelNames = $this->getModelsNames($modelsPath);
        $modelNames = $this->removeDuplicateModelNames($modelNames);

        foreach ($modelNames as $modelName) {
            $model = app($modelName);

            $nodeDataArray[] = $this->getNodes($model);
        }
        return $nodeDataArray;
    }

    function removeDuplicateModelNames($modelNames)
    {
        $finalModelNames = collect($modelNames)
            ->map(function($modelName) {
                $model = app($modelName);
                return [
                    'model_name' => $modelName,
                    'table' => $model->getTable(),
                ];
            })
            ->unique('table')
            ->pluck('model_name');

        return $finalModelNames->all();
    }

    private function extractNamespace($file)
    {
        $ns = NULL;
        $handle = fopen($file, "r");
        if ($handle) {
            while (($line = fgets($handle)) !== false) {
               if (strpos($line, 'namespace') === 0) {
                  $parts = explode(' ', $line);
                  $ns = rtrim(trim($parts[1]), ';');
                  break;
               } else if (strpos($line, '<?php namespace') === 0) {
                  $parts = explode(' ', $line);
                  $ns = rtrim(trim($parts[2]), ';');
                  break;
               }
            }
            fclose($handle);
        }
        return $ns;
    }

    /**
     * Relationships
     *
     * @param Model $model
     * @return array of relationships
     */
    private function getRelationships(Model $model): array
    {
        $relationships = [];
        $model = new $model;

        foreach ((new ReflectionClass($model))->getMethods(ReflectionMethod::IS_PUBLIC) as $method) {
            if ($method->class != get_class($model)
                || !empty($method->getParameters())
                || $method->getName() == __FUNCTION__
            ) {
                continue;
            }

            try {
                $return = $method->invoke($model);
                // check if not instance of Relation
                if (!($return instanceof Relation)) {
                    continue;
                }
                $relationType = (new ReflectionClass($return))->getShortName();
                $modelName = (new ReflectionClass($return->getRelated()))->getName();

                $foreignKey = $return->getQualifiedForeignKeyName();
                $parentKey = $return->getQualifiedParentKeyName();

                $relationships[$method->getName()] = [
                    'type'        => $relationType,
                    'model'       => $modelName,
                    'foreign_key' => $foreignKey,
                    'parent_key'  => $parentKey,
                ];
            } catch (QueryException $e) {
                // ignore
            } catch (TypeError $e) {
                // ignore
            } catch (Throwable $e) {
                // throw $e;
                //ignore
            }
        }

        return $relationships;
    }

    private function isPrimaryKey($column, $model){
        $keyName = $model->getKeyName();
        if (is_array($keyName)) {
            if (in_array($column, $keyName)) {
                return true;
            }
        } else {
            $isPrimaryKey = $column == $keyName;
            if ($column == $keyName) {
                return true;
            }
        }
    }

    private function getNodes(Model $model): array
    {
        $nodeItems = [];
        $columns = Schema::getColumnListing($model->getTable());

        foreach ($columns as $column) {

            $attrs = [];

            if ($this->isPrimaryKey($column, $model)) {
                $attrs[] = 'PK';
            }


            $nodeItems[] = [
                "name"   => $column,
                "attributes" => implode(', ', $attrs),
                "type"   => Schema::getColumnType($model->getTable(), $column),
            ];
        }
        return [
            "key"    => $model->getTable(),
            "schema" => $nodeItems
        ];
    }

    private function getLinks(Model $model)
    {

        $relationships = $this->getRelationships($model);
        $linkItems = [];
        foreach ($relationships as $relationship) {
            $fromTable = $model->getTable();
            $toTable = app($relationship['model'])->getTable();

            // check if is array for multiple primary key
            if (is_array($relationship['foreign_key']) || is_array($relationship['parent_key'])) {
                // TODO add support for multiple primary keys
                $fromPort = ".";
                $toPort = ".";
            } else {
                $isBelongsTo = ($relationship['type'] == "BelongsTo" || $relationship['type'] == "BelongsToMany");
                $fromPort = $isBelongsTo ? $relationship["foreign_key"] : $relationship["parent_key"];
                $toPort   = $isBelongsTo ? $relationship["parent_key"] : $relationship["foreign_key"];
            }


            $linkItems[] = [
                "from"     => $fromTable,
                "to"       => $toTable,
                "fromText" => config('laravel-erd.from_text.'.$relationship['type']),
                "toText"   => config('laravel-erd.to_text.'.$relationship['type']),
                "fromPort" => $this->parsePort($fromPort, $fromTable),
                "toPort"   => explode(".", $toPort)[1],//strip tablename
                "type"     => $relationship['type'],
            ];
        }

        return $linkItems;
    }

    private function parsePort($port, $table){
      if ($table == explode(".", $port)[0]) {
         return '';
      }
      return explode(".", $port)[1]; //strip tablename
    }
}
