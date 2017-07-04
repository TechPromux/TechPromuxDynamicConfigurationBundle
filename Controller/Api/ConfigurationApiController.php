<?php

namespace TechPromux\Bundle\DynamicConfigurationBundle\Controller\Api;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use FOS\RestBundle\Controller\Annotations as FOSRest;
use FOS\RestBundle\Controller\FOSRestController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;

/**
 * Description of ConfiguracionApiController
 *
 * @FOSRest\Route("/configuration")
 * 
 */
class ConfigurationApiController extends FOSRestController {

    //-------------------------------------------------------------------------------------------

    /**
     * Get value by code from configurations vars
     * 
     * @ApiDoc(
     *   resource = true,
     *   description = "Get configuration value",
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     404 = "Returned when the resource is not found"
     *   }
     * )
     *
     * @FOSRest\Get("/{code}",options={"expose"=true})
     * 
     * @Security("has_role('ROLE_API')")
     * 
     * @param Request $request the request object
     * @param mixed   $code     the configuration code
     *
     * @return mixed
     *
     * @throws NotFoundHttpException when resource not exist
     */
    public function getAction(Request $request, $code) {
        $value = $this->getUtilDynamicConfigurationManager()->getConfigurationValueByCode($code);
        $view = $this->view($value);
        return $this->handleView($view);
    }

    /**
     * @return object|\TechPromux\Bundle\DynamicConfigurationBundle\Manager\UtilDynamicConfigurationManager
     */
    protected function getUtilDynamicConfigurationManager()
    {
        return $this->get('techpromux_dynamic_configuration.manager.util_dynamic_configuration');
    }
}
