<?php

namespace FondOfSpryker\Yves\GoogleTagManager\Plugin\VariableBuilder\DefaultVariables;

class InternalVariableBuilderPlugin implements DefaultVariableBuilderPluginInterface
{
    public const FIELD_CLIENT_IP = 'clientIp';
    public const FIELD_INTERNAL_IPS = 'internalIps';
    public const FIELD_INTERNAL_TRAFFIC = 'internalTraffic';

    /**
     * @param array $variables
     * @param array $params
     *
     * @return array
     */
    public function handle(array $variables, array $params = []): array
    {
        if (!isset($params[static::FIELD_CLIENT_IP], $params[static::FIELD_INTERNAL_IPS])) {
            return [];
        }

        if (!\in_array($params[static::FIELD_CLIENT_IP], $params[static::FIELD_INTERNAL_IPS])) {
            return [];
        }

        return [static::FIELD_INTERNAL_TRAFFIC => true];
    }
}
