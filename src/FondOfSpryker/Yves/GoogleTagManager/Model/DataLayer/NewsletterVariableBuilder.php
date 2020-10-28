<?php


namespace FondOfSpryker\Yves\GoogleTagManager\Model\DataLayer;

use FondOfSpryker\Shared\GoogleTagManager\GoogleTagManagerConstants;

class NewsletterVariableBuilder
{
    /**
     * @var array
     */
    protected $newsletterVariableBuilderPlugins;

    /**
     * @param \FondOfSpryker\Yves\GoogleTagManager\Plugin\VariableBuilder\NewsletterVariables\NewsletterVariablesPluginInterface[] $defaultVariableBuilderPlugins
     */
    public function __construct(array $newsletterVariableBuilderPlugins)
    {
        $this->newsletterVariableBuilderPlugins = $newsletterVariableBuilderPlugins;
    }

    /**
     * @param string $page
     *
     * @return array
     */
    public function getVariables(string $page): array
    {
        $variables = [
            'pageType' => GoogleTagManagerConstants::PAGE_TYPE_NEWSLETTER_SUBSCRIBE,
        ];

        return $this->executePlugins($variables);
    }

    /**
     * @param array $variables
     * @param \Generated\Shared\Transfer\GoogleTagManagerCategoryTransfer $googleTagManagerCategoryTransfer
     *
     * @return array
     */
    protected function executePlugins(array $variables): array
    {
        foreach ($this->newsletterVariableBuilderPlugins as $plugin) {
            $variables = \array_merge($variables, $plugin->handle($variables));
        }

        return $variables;
    }
}
