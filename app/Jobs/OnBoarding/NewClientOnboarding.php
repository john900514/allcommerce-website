<?php

namespace AllCommerce\Jobs\OnBoarding;

use AllCommerce\Aggregates\Clients\ClientConfigAggregate;
use AllCommerce\Clients;
use AllCommerce\Models\PaymentGateways\PaymentProviders;
use AllCommerce\Services\OnBoarding\ClientOnBoardingService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class NewClientOnboarding implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $client;
    /**
     * Create a new job instance.
     * @param Clients $client
     * @return void
     */
    public function __construct(Clients $client)
    {
        $this->client = $client;
    }

    /**
     * Execute the job.
     * @param ClientOnBoardingService $service
     * @return void
     */
    public function handle(ClientOnBoardingService $service)
    {
        $aggy = ClientConfigAggregate::retrieve($this->client->id)
            ->setClient($this->client);

        if($enabled = $service->createDefaultEnabledPaymentGateway($this->client->id))
        {
            $aggy = $aggy->setDefaultEnabledPaymentProvider($enabled);
        }

        if($token = $service->createNewClientApiToken($this->client->id))
        {
            $aggy = $aggy->setNewClientApiToken($token);
        }

        if($sms_features = $service->createNewSMSFeatures($this->client->id))
        {
            $aggy = $aggy->setNewSMSFeatures($sms_features['profiles'], $sms_features['one_click']);
        }

        if($menu_options = $service->createNewMenuOptions($this->client->id))
        {
            $aggy = $aggy->setNewMenuOptions();
        }

        $service->createNewRoles($this->client->id);

        $aggy->persist();
    }
}
