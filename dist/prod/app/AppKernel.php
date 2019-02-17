<?php

use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Config\Loader\LoaderInterface;

class AppKernel extends Kernel
{
    public function registerBundles()
    {
        $bundles = array(
            new Symfony\Bundle\FrameworkBundle\FrameworkBundle(),
            new Symfony\Bundle\SecurityBundle\SecurityBundle(),
            new Symfony\Bundle\TwigBundle\TwigBundle(),
            new Symfony\Bundle\MonologBundle\MonologBundle(),
            new Symfony\Bundle\SwiftmailerBundle\SwiftmailerBundle(),
            new Doctrine\Bundle\DoctrineBundle\DoctrineBundle(),
            new Sensio\Bundle\FrameworkExtraBundle\SensioFrameworkExtraBundle(),
            new AppBundle\AppBundle(),
            new JMS\SerializerBundle\JMSSerializerBundle(),
            new FOS\RestBundle\FOSRestBundle(),
            new FOS\UserBundle\FOSUserBundle(),
            new Nelmio\CorsBundle\NelmioCorsBundle(),
            new Nelmio\ApiDocBundle\NelmioApiDocBundle(),
            new Zfzmyrjlmj\AuthenticationBundle\ZfzmyrjlmjAuthenticationBundle(),
            new FOS\OAuthServerBundle\FOSOAuthServerBundle(),
            new Zfzmyrjlmj\ServeBundle\ZfzmyrjlmjServeBundle(),
            new Zfzmyrjlmj\PatientBundle\ZfzmyrjlmjPatientBundle(),
            new Chronoheed\AuthBundle\ChronoheedAuthBundle(),
            new Chronoheed\ServeBundle\ChronoheedServeBundle(),
            new Chronoheed\PatientBundle\ChronoheedPatientBundle(),
            new Chronoheed\CalendarBundle\ChronoheedCalendarBundle(),
            new Liip\ImagineBundle\LiipImagineBundle(),
            new Chronoheed\PaymentBundle\ChronoheedPaymentBundle(),
            new Chronoheed\UserBundle\ChronoheedUserBundle(),
            new Chronoheed\ReportsBundle\ChronoheedReportsBundle(),
            new Chronoheed\NotificationBundle\ChronoheedNotificationBundle(),
        );

        if (in_array($this->getEnvironment(), array('dev', 'test'), true)) {
            $bundles[] = new Symfony\Bundle\DebugBundle\DebugBundle();
            $bundles[] = new Symfony\Bundle\WebProfilerBundle\WebProfilerBundle();
            $bundles[] = new Sensio\Bundle\DistributionBundle\SensioDistributionBundle();
            $bundles[] = new Sensio\Bundle\GeneratorBundle\SensioGeneratorBundle();
        }

        return $bundles;
    }

    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        $loader->load($this->getRootDir().'/config/config_'.$this->getEnvironment().'.yml');
    }
}
