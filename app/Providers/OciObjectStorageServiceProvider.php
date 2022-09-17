<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Aws\S3\S3Client;
use Illuminate\Filesystem\AwsS3V3Adapter;
// use Illuminate\Filesystem\AwsS3V3Adapter;
use League\Flysystem\Filesystem;
use Storage;
use Illuminate\Filesystem\FilesystemAdapter;
use League\Flysystem\AwsS3V3\AwsS3V3Adapter as S3Adapter;

class OciObjectStorageServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        if (config('filesystems.default') != 'oci') {
            return;
        }

        Storage::extend('s3', function ($app, $config) {
            $client = new S3Client([
                'credentials' => [
                    'key'    => $config['key'],
                    'secret' => $config['secret'],
                ],
                'region' => $config['region'],
                'version' => 'latest',
                'bucket_endpoint' => true,
                'endpoint' => $config['url']
            ]);

            $adapter = new S3Adapter($client, $config['bucket']);

            return new AwsS3V3Adapter(
                new Filesystem($adapter, $config),
                $adapter,
                $config,
                $client
            );
        });
    }
}
