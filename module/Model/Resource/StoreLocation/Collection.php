<?php

/**
 * StoreLocation collection
 */
namespace MagentoEse\InStorePickup\Model\Resource\StoreLocation;

class Collection extends \Magento\Framework\Model\Resource\Db\Collection\AbstractCollection
{

    /**
     * database column name for calculated distance
     */
    const DISTANCE_COLUMN = 'distance';

    /**
     * The radius of the earth in miles used for location distance calculations
     */
    const EARTH_RADIUS_IN_MILES = 3959;

    /**
     * The radius of the earth in kilometers used for location distance calculations
     */
    const EARTH_RADIUS_IN_KILOMETERS = 6371;

    /**
     * @var \MagentoEse\InStorePickup\Model\Resource\ZipcodeLocationFactory
     */
    private $zipLocFactory;

    /**
     * @param \Magento\Framework\Data\Collection\EntityFactory $entityFactory
     * @param \Psr\Log\LoggerInterface $logger
     * @param \Magento\Framework\Data\Collection\Db\FetchStrategyInterface $fetchStrategy
     * @param \Magento\Framework\Event\ManagerInterface $eventManager
     * @param \Magento\Sales\Model\Resource\Report $resource
     * @param \MagentoEse\InStorePickup\Model\Resource\ZipcodeLocationFactory $zipLocFactory
     * @param mixed $connection
     */
    public function __construct(
        \Magento\Framework\Data\Collection\EntityFactory $entityFactory,
        \Psr\Log\LoggerInterface $logger,
        \Magento\Framework\Data\Collection\Db\FetchStrategyInterface $fetchStrategy,
        \Magento\Framework\Event\ManagerInterface $eventManager,
        \MagentoEse\InStorePickup\Model\Resource\ZipcodeLocationFactory $zipLocFactory,
        \Magento\Framework\DB\Adapter\AdapterInterface $connection = null,
        \Magento\Sales\Model\Resource\Report $resource = null
    ) {
        parent::__construct($entityFactory, $logger, $fetchStrategy, $eventManager, $connection, $resource);

        // We need an instance of ZipcodeLocation for a load method
        $this->zipLocFactory = $zipLocFactory;
    }

    /**
     * Standard collection initialization
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('MagentoEse\InStorePickup\Model\StoreLocation', 'MagentoEse\InStorePickup\Model\Resource\StoreLocation');
    }

    /**
     * Initialize select object
     * Instead of getting all column results, here we specify the relevant columns.
     *
     * @return $this
     */
    protected function _initSelect()
    {
        $this->getSelect()
            ->from(
                ['main_table' => $this->getResource()->getMainTable()],
                [
                    'id',
                    'name',
                    'street_address',
                    'city',
                    'state',
                    'postal_code',
                    'phone'
                ]);

        return $this;
    }

    /**
     * Filter store collection by distance from zipcode location
     *
     * @param string $zipcode
     * @param int $distance
     * @return $this
     */
    public function addZipcodeDistanceFilter($zipcode, $distance = 50)
    {
        $zipLoc = $this->zipLocFactory->create();
        $geomPointText = $zipLoc->getGeomPointTextByZipcode($zipcode);

        $this->addDistanceColumn($geomPointText);
        $this->addDistanceFilter($distance, $geomPointText);

        return $this;
    }

    /**
     * Add distance column
     *
     * @param string $originGeomPointText
     * @return $this
     */
    public function addDistanceColumn($originGeomPointText)
    {
        $this->getSelect()
            ->columns(array(
                Collection::DISTANCE_COLUMN => New \Zend_Db_Expr(
                    $this->getDistanceColumnDefinition($originGeomPointText)
                )
            ));
        return $this;
    }

    /**
     * Filter by distance
     *
     * @param int $distance
     * @return $this
     */
    public function addDistanceFilter($distance, $originGeomPointText)
    {
        if (!empty($distance)) {
            $select = $this->getSelect();
            $condition = $select->getConnection()->prepareSqlCondition(
                New \Zend_Db_Expr(
                    $this->getDistanceColumnDefinition($originGeomPointText)
                ), ['lt' => $distance]);
            $select->where($condition);
        }
        return $this;
    }

    /**
     * Return definition for calculated distance column
     * This takes the origin point coordinates and calculates the distance from the record's point coordinates
     *
     * @param string $originGeomPointText
     * @return string
     */
    protected function getDistanceColumnDefinition($originGeomPointText)
    {
        return sprintf(
            "(
                %s * acos (
                  cos ( radians(X(GeomFromText('%s'))) )
                  * cos( radians( X(GeomFromText(concat('POINT(', cast(lat_deg as char(15)), ' ', cast(lon_deg as char(15)), ')'))) ) )
                  * cos( radians( Y(GeomFromText(concat('POINT(', cast(lat_deg as char(15)), ' ', cast(lon_deg as char(15)), ')'))) ) - radians(Y(GeomFromText('%s'))) )
                  + sin ( radians(X(GeomFromText('%s'))) )
                  * sin( radians( X(GeomFromText(concat('POINT(', cast(lat_deg as char(15)), ' ', cast(lon_deg as char(15)), ')'))) ) )
                )
            )", Collection::EARTH_RADIUS_IN_MILES, $originGeomPointText, $originGeomPointText, $originGeomPointText
        );
    }
}
