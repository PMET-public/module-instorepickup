<?php

namespace MagentoEse\InStorePickup\Model\Resource;

use Magento\Framework\Model\Resource\Db\AbstractDb;

class ZipcodeLocation extends AbstractDb
{
    /**
     * database column name for geometic point location as text
     */
    const POINT_COLUMN = 'geom_point_as_text';

    /**
     * Initialize resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('directory_location_us_zip_code', 'zip');
    }

    /**
     * Get a constructed geometric point location as text from DB by zipcode
     *
     * The return value should look like 'POINT(42.833261 -74.058015)'
     * The POINT(latitude longitude) is a mysql geometric point representation we use here for latitude longitude coordinates.
     * By using this understood formatting we can pass this value into other queries that run calculations on
     * location specific data. The MySQL functions AsText() and GeomFromText() provide a means of easily converting coordinate values.
     *
     * @param string $zipcode
     * @return string
     */
    public function getGeomPointTextByZipcode($zipcode)
    {
        $connection = $this->getConnection();
        $bind['zip'] = $zipcode;
        $select = $connection
            ->select()
            ->from($this->getMainTable(), [])
            ->columns(
                [
                    ZipcodeLocation::POINT_COLUMN =>
                        new \Zend_Db_Expr("AsText(GeomFromText(concat('POINT(', cast(lat as char(15)), ' ', cast(lon as char(15)), ')')))")
                ]
            )
            ->where('zip = :zip');

        $geomPointText = $connection->fetchOne($select, $bind);

        if (!$geomPointText) {
            return '';
        }

        return $geomPointText;
    }
}
