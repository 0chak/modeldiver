<?php

namespace Chak\Modeldiver;

use Spatie\LaravelPackageTools\PackageServiceProvider;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\Commands\InstallCommand;

class ModeldiverPackageServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('modeldiver')
            // ->hasConfigFile()
            ->hasViews()
            // ->hasViewComponent('spatie', Alert::class)
            // ->hasViewComposer('*', MyViewComposer::class)
            // ->sharesDataWithAllViews('downloads', 3)
            // ->hasTranslations()
            // ->hasAssets()
            // ->publishesServiceProvider('ModelDiverServiceProvider')
            ->hasRoute('web')
            // ->hasMigration('create_package_tables')
            // ->hasCommand(YourCoolPackageCommand::class)
            ->hasInstallCommand(function(InstallCommand $command) {
                $command
                    // ->publishConfigFile()
                    // ->publishAssets()
                    // ->publishMigrations()
                    ->copyAndRegisterServiceProviderInApp();
                    // ->askToStarRepoOnGitHub();
            });
    }
}
