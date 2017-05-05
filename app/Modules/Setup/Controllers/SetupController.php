<?php

/**
 * This file is part of FusionInvoice.
 *
 * (c) FusionInvoice, LLC <jessedterry@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FI\Modules\Setup\Controllers;

use Artisan;
use FI\Http\Controllers\Controller;
use FI\Modules\Setup\Validators\SetupValidator;
use FI\Modules\Users\Repositories\UserRepository;
use Hash;

class SetupController extends Controller
{
    public function __construct(
        SetupValidator $setupValidator,
        UserRepository $userRepository
    )
    {
        parent::__construct();
        $this->userRepository = $userRepository;
        $this->setupValidator = $setupValidator;
    }

    public function index()
    {
        return view('setup.index')
            ->with('license', file_get_contents(public_path() . '/LICENSE'));
    }

    public function postIndex()
    {
        $validator = $this->setupValidator->getLicenseValidator(request()->all());

        if ($validator->fails())
        {
            return redirect()->route('setup.index');
        }

        return redirect()->route('setup.prerequisites');
    }

    public function prerequisites()
    {
        $errors          = [];
        $versionRequired = '5.3.7';
        $dbDriver        = config('database.default');
        $dbConfig        = config('database.connections.' . $dbDriver);

        if (version_compare(phpversion(), $versionRequired, '<'))
        {
            $errors[] = sprintf(trans('fi.php_version_error'), $versionRequired);
        }

        if ($dbDriver == 'sqlite')
        {
            if (!file_exists($dbConfig['database']))
            {
                $errors[] = trans('fi.sqlite_database_not_exist');
            }
        }
        else
        {
            if (!$dbConfig['host'] or !$dbConfig['database'] or !$dbConfig['username'] or !$dbConfig['password'])
            {
                $errors[] = trans('fi.database_not_configured');
            }
        }

        if (!$errors)
        {
            return redirect()->route('setup.migration');
        }

        return view('setup.prerequisites')
            ->with('errors', $errors);
    }

    public function migration()
    {
        return view('setup.migration');
    }

    public function postMigration()
    {
        $migrationRepository = app('migration.repository');

        if (!$migrationRepository->repositoryExists())
        {
            $migrationRepository->createRepository();
        }

        $migrator = app('migrator');

        $migrator->run(database_path('migrations'));

        return response()->json(['notes' => $migrator->getNotes()]);
    }

    public function account()
    {
        if (!$this->userRepository->count())
        {
            return view('setup.account');
        }

        return redirect()->route('setup.complete');
    }

    public function postAccount()
    {
        if (!$this->userRepository->count())
        {
            $input = request()->all();

            $validator = $this->setupValidator->getUserValidator($input);

            if ($validator->fails())
            {
                return redirect()->route('setup.account')
                    ->withErrors($validator)
                    ->withInput();
            }

            unset($input['password_confirmation']);

            $this->userRepository->create($input);
        }

        return redirect()->route('setup.complete');
    }

    public function complete()
    {
        return view('setup.complete');
    }
}