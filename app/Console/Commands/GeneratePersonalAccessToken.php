<?php

namespace App\Console\Commands;

use App\Helpers\Helper;
use Illuminate\Console\Command;
use App\Models\User;
use Laravel\Passport\TokenRepository;
use Illuminate\Contracts\Validation\Factory as ValidationFactory;

class GeneratePersonalAccessToken extends Command
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'snipeit:make-api-key 
                        {user : The ID of the user to create the token for}
                        {--name= : The name of the new API token}
                        {--key-only= : Only return the value of the API key}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';


    /**
     * The token repository implementation.
     *
     * @var \Laravel\Passport\TokenRepository
     */
    protected $tokenRepository;


    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(TokenRepository $tokenRepository, ValidationFactory $validation)
    {
        $this->validation = $validation;
        $this->tokenRepository = $tokenRepository;
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {

        if ($this->option('name')=='') {
            $accessTokenName = 'CLI Auth Token';
        }

        if ($this->argument('user')=='') {
            return false;
        }

        if ($user = User::find($this->argument('user'))) {

            if ($this->option('key-only')=='true') {
                $this->info($user->createToken($accessTokenName)->accessToken);
            } else {
                $this->warn('Your API Token has been created. Be sure to copy this token now, as it will not be accessible again.');
                $this->info('API Token Name: '.$accessTokenName);
                $this->info('API Token: '.$user->createToken($accessTokenName)->accessToken);
            }
        } else {
           return $this->error('ERROR: Invalid user. API key was not created.');
        }




    }
}
