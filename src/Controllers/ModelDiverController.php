<?php

namespace Chak\Modeldiver\Controllers;

use Chak\Modeldiver\ModelMap;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Storage;

class ModelDiverController extends Controller
{
   private $modelMap;

   public function __construct(ModelMap $modelMap)
   {
      $this->modelMap = $modelMap;
   }

   public function index()
   {
      $modelsPath = base_path('app/Models');
      $settings = [];
      if (Storage::exists('modelmap.json')) {
         $settings = json_decode(Storage::read('modelmap.json'));
      }
      return view('modeldiver::md')->with([
         'nodes' => $this->modelMap->getNodeDataArray($modelsPath),
         'links' => $this->modelMap->getLinkDataArray($modelsPath),
         'settings' => $settings,
      ]);
   }

   public function saveposition(Request $r){
      $data = [];
      if (Storage::exists('modelmap.json')){
         $data = json_decode(Storage::read('modelmap.json'), true);
      }
      $data[$r->id]['x'] = $r->x;
      $data[$r->id]['y'] = $r->y;

      Storage::write('modelmap.json', json_encode($data));

      return response()->json([
         "success" => true,
         "message" => "",
         "data" => []
      ], 200);

   }
}
