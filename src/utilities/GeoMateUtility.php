<?php
/**
 * GeoMate plugin for Craft CMS 5.x
 *
 * Look up visitors location data based on their IP and easily redirect them to the correct site..
 *
 * @link      https://www.vaersaagod.no
 * @copyright Copyright (c) 2024 Værsågod
 */

namespace vaersaagod\geomate\utilities;

use Craft;
use craft\base\Utility;
use vaersaagod\geomate\assetbundles\GeoMateAssets;
use vaersaagod\geomate\GeoMate;
use vaersaagod\geomate\models\Settings;
use yii\base\InvalidConfigException;

/**
 * @author    Værsågod
 * @package   GeoMate
 * @since     1.0.0
 */
class GeoMateUtility extends Utility
{
    // Static
    // =========================================================================

    /**
     * @inheritdoc
     */
    public static function displayName(): string
    {
        return Craft::t('geomate', 'GeoMate');
    }

    /**
     * @inheritdoc
     */
    public static function id(): string
    {
        return 'geomate-utility';
    }

    /**
     * @inheritdoc
     */
    public static function iconPath(): ?string
    {
        return Craft::getAlias('@vaersaagod/geomate/icon-mask.svg');
    }

    /**
     * @inheritdoc
     */
    public static function badgeCount(): int
    {
        return GeoMate::getInstance()->database->hasDatabase() ? 0 : 1;
    }
    
    /**
     * @inheritdoc
     */
    public static function icon(): ?string
    {
        return 'globe';
    }

    /**
     * @inheritdoc
     */
    public static function contentHtml(): string
    {
        /** @var Settings $settings */
        $settings = GeoMate::getInstance()->getSettings();
        
        try {
            Craft::$app->getView()->registerAssetBundle(GeoMateAssets::class);
        } catch (InvalidConfigException) {
            return Craft::t('geomate', 'Could not load asset bundle');
        }
        
        return Craft::$app->getView()->renderTemplate(
            'geomate/utility/_render',
            [
                'hasDatabase' => GeoMate::getInstance()->database->hasDatabase(),
                'dbTimestamp' => GeoMate::getInstance()->database->getDatabaseTimestamp(),
                'settings' => $settings,
                'memoryLimit' => ini_get('memory_limit'),
            ]
        );
    }
}
