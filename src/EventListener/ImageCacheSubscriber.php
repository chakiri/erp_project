<?php

namespace App\EventListener;


use App\Entity\Product;
use App\Entity\Profile;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Events;
use Liip\ImagineBundle\Imagine\Cache\CacheManager;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Vich\UploaderBundle\Templating\Helper\UploaderHelper;

class ImageCacheSubscriber implements EventSubscriber
{
    private $cacheManager;

    private $helper;

    public function getSubscribedEvents(){
        return [
            Events::preUpdate,
            Events::preRemove
        ];
    }

    public function __construct(CacheManager $cacheManager, UploaderHelper $helper)
    {
        $this->cacheManager = $cacheManager;
        $this->helper = $helper;
    }

    public function preUpdate(LifecycleEventArgs $args){
        $entity = $args->getEntity();
        if (!$entity instanceof Product && !$entity instanceof Profile){
            return;
        }

        if ($entity->getImageFile() instanceof UploadedFile){
            $this->cacheManager->remove($this->helper->asset($entity, 'imageFile'));
        }
    }

    public function preRemove(LifecycleEventArgs $args){
        $entity = $args->getEntity();
        if (!$entity instanceof Product && !$entity instanceof Profile){
            return;
        }

        $this->cacheManager->remove($this->helper->asset($entity, 'imageFile'));
    }

}