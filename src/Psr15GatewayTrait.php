<?php
/**
 * Created by PhpStorm.
 * User: jstormes
 * Date: 5/2/2018
 * Time: 10:54 AM
 */

namespace JStormes\HtmlGateway;

use Zend\Hydrator\ClassMethods;
use Psr\Http\Message\ServerRequestInterface;


trait Psr15GatewayTrait
{
    private $hydrator = null;
    
    /**
     * @param $Prototype
     * @return $this
     */
    public function setPrototype($Prototype)
    {
        $this->setData($Prototype);
        return $this;
    }

    public function setHydrator($Hydrator)
    {
        $this->hydrator = $Hydrator;
    }

    /**
     * @param ServerRequestInterface $request
     * @return mixed
     */
    public function fetch(ServerRequestInterface $request)
    {
        $formData = $request->getParsedBody();

        $data = clone($this->data);

        $this->hydrator->hydrate($formData, $data);

        $this->data = $data;

        return $data;
    }

    /**
     * Process a PSR-7 request from browser. $data MUST support record pattern.
     * Use prototype pattern for priming data.
     *
     * @param $request
     * @param string $buttonName
     * @return mixed
     */
    public function process(ServerRequestInterface $request, string $buttonName='action')
    {

        $form = $request->getParsedBody();

        if (isset($form[$buttonName])) {
            if ($request->getMethod() === 'POST') {

                $this->data = $this->fetch($request);

                if ($form[$buttonName] === 'save') {
                    $this->data->save();
                }

                if ($form[$buttonName] === 'delete') {
                    $this->data->delete();
                }
            }
        }

        return $this->data;
    }
}