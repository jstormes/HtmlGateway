<?php
/**
 * Created by PhpStorm.
 * User: james.s
 * Date: 12/20/2017
 * Time: 2:08 PM
 *
 * Lightweight html to/from entity gateway.
 */

namespace Html;

use Zend\Hydrator\ClassMethods;
use Psr\Http\Message\ServerRequestInterface;

class AbstractHtmlGateway
{
    /**
     * Relative path to template file.
     * @var null|string
     */
    private $template=null;

    /**
     * Array of helpers.
     * @var array
     */
    private $helpers=[];

    /**
     * @var AbstractHtmlGateway[string section][]
     */
    private $gateways=[];

    /**
     * @var
     */
    private $data;

    /**
     * @var null|ClassMethods
     */
    private $hydrator = null;

    /**
     * AbstractHtmlGateway constructor.
     * @param string|null $template
     * @param array $helpers
     */
    public function __construct(string $template = null, $helpers = [])
    {
        $this->template = $template;
        $this->helpers  = $helpers;

        $this->hydrator = new ClassMethods();
    }

    /**
     * Call a view helper using magic methods.
     *
     * @param $name
     * @param $arguments
     * @return mixed
     * @throws \Exception
     */
    public function __call($name, $arguments)
    {
        if (isset($this->helpers[$name])) {
            $function = $this->helpers[$name];
            return $function->execute($name, $arguments, $this);
        }
        throw new \Exception($name." not found");
    }

    /**
     * @param $name
     * @param $helper
     * @return $this
     */
    public function addHelper($name,$helper) {
        $this->helpers[$name] = $helper;
        return $this;
    }

    /**
     * @param $data
     * @return $this
     */
    public function setData($data)
    {
        $this->data = $data;
        return $this;
    }

    /**
     * @return mixed
     */
    public function data()
    {
        return $this->data;
    }

    /**
     * @param null $data
     * @return string
     * @throws \Exception
     * @throws \Throwable
     */
    public function render($data=null){

        if ($data!==null) {
            $this->setData($data);
        }

        if (!file_exists($this->template)) {
            throw new \Exception("template file not found");
        }

        try {
            $level = ob_get_level();
            ob_start();
            include $this->template;
            $content = ob_get_clean();

            return $content;
        } catch (\Throwable $e) {
            while (ob_get_level() > $level) {
                ob_end_clean();
            }

            throw $e;
        } catch (\Exception $e) {
            while (ob_get_level() > $level) {
                ob_end_clean();
            }

            throw $e;
        }

    }

    /**
     * @param string $section
     * @param AbstractHtmlGateway $gateway
     * @param null $data
     * @return $this
     */
    public function addToSection(string $section, $gateway, $data=null){

        if ($data!==null) {
            $gateway->setData($data);
        }

        $this->gateways[$section][]=$gateway;
        return $this;
    }

    /**
     * Render a section.
     *
     * @param string $sectionName
     * @return string
     */
    public function section(string $sectionName)
    {
        $html = '';

        if (isset($this->gateways[$sectionName])) {
            foreach ($this->gateways[$sectionName] as $gateway) {
                $html .= $gateway->render();
            }
        }

        return $html;
    }

    /**
     * Escape string.
     *
     * @param  string      $string
     * @return string
     */
    public function escape($string)
    {
        static $flags;

        if (!isset($flags)) {
            $flags = ENT_QUOTES | (defined('ENT_SUBSTITUTE') ? ENT_SUBSTITUTE : 0);
        }

        return htmlspecialchars($string, $flags, 'UTF-8');
    }

    /**
     * Alias for escape
     *
     * @param $string
     * @return string
     */
    public function e($string)
    {
        return $this->escape($string);
    }


    /**
     * @param $Prototype
     * @return $this
     */
    public function setPrototype($Prototype)
    {
        $this->setData($Prototype);
        return $this;
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
     * @param null $data
     * @return mixed
     */
    public function process($request, $buttonName='action', $data=null){

        if ($data!==null){
            $this->setData($data);
        }

        $form = $request->getParsedBody();

        if (isset($form[$buttonName])) {
            if ($request->getMethod() === 'POST') {

                $this->data = $this->fetch($request, $data);

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