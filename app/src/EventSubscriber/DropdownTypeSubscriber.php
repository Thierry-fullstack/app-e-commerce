<?php

namespace App\EventSubscriber;


use App\Repository\TypeRepository;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Twig\Environment;

class DropdownTypeSubscriber implements EventSubscriberInterface
{
    const array ROUTES = ['app_main'];

    public function __construct(private readonly TypeRepository $typeRepository ,private readonly Environment $twig)
    {
    }

    public function injectGlobalVariable(RequestEvent $event): void
    {
        $route = $event->getRequest()->get('_route');
        if(in_array($route,DropdownTypeSubscriber::ROUTES)){
          //  $types = $this->typeRepository->findBy([],['name'=>'asc']);
            $types = $this->typeRepository->findBy([],['name'=>'ASC']);

            $this->twig->addGlobal('allTypes',$types);
        }
    }

    public static function getSubscribedEvents(): array
    {
        return [KernelEvents::REQUEST=>'injectGlobalVariable'];
    }
}
