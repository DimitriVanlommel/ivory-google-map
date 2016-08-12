<?php

/*
 * This file is part of the Ivory Google Map package.
 *
 * (c) Eric GELOEN <geloen.eric@gmail.com>
 *
 * For the full copyright and license information, please read the LICENSE
 * file that was distributed with this source code.
 */

namespace Ivory\GoogleMap\Service\Directions;

use Http\Client\HttpClient;
use Http\Message\MessageFactory;
use Ivory\GoogleMap\Base\Bound;
use Ivory\GoogleMap\Base\Coordinate;
use Ivory\GoogleMap\Overlay\EncodedPolyline;
use Ivory\GoogleMap\Service\AbstractService;
use Ivory\GoogleMap\Service\Base\Distance;
use Ivory\GoogleMap\Service\Base\Duration;

/**
 * @author GeLo <geloen.eric@gmail.com>
 */
class Directions extends AbstractService
{
    /**
     * @param HttpClient     $client
     * @param MessageFactory $messageFactory
     */
    public function __construct(HttpClient $client, MessageFactory $messageFactory)
    {
        parent::__construct($client, $messageFactory, 'http://maps.googleapis.com/maps/api/directions');
    }

    /**
     * @param DirectionsRequest $request
     *
     * @return DirectionsResponse
     */
    public function route(DirectionsRequest $request)
    {
        $response = $this->getClient()->sendRequest($this->createRequest($request->buildQuery()));
        $data = $this->parse((string) $response->getBody());

        return $this->buildResponse($data);
    }

    /**
     * @param string $data
     *
     * @return mixed[]
     */
    private function parse($data)
    {
        if ($this->getFormat() === self::FORMAT_JSON) {
            return json_decode($data, true);
        }

        return $this->getXmlParser()->parse($data, [
            'leg'   => 'legs',
            'route' => 'routes',
            'step'  => 'steps',
        ]);
    }

    /**
     * @param mixed[] $data
     *
     * @return DirectionsResponse
     */
    private function buildResponse(array $data)
    {
        $response = new DirectionsResponse();
        $response->setStatus($data['status']);
        $response->setRoutes(isset($data['routes']) ? $this->buildRoutes($data['routes']) : []);

        return $response;
    }

    /**
     * @param mixed[] $data
     *
     * @return DirectionsRoute[]
     */
    private function buildRoutes(array $data)
    {
        $routes = [];
        foreach ($data as $item) {
            $routes[] = $this->buildRoute($item);
        }

        return $routes;
    }

    /**
     * @param mixed[] $data
     *
     * @return DirectionsRoute
     */
    private function buildRoute(array $data)
    {
        $route = new DirectionsRoute();
        $route->setCopyrights(isset($data['copyrights']) ? $data['copyrights'] : null);
        $route->setLegs($this->buildLegs($data['legs']));
        $route->setOverviewPolyline(new EncodedPolyline($data['overview_polyline']['points']));
        $route->setSummary(isset($data['summary']) ? $data['summary'] : null);
        $route->setWarnings(isset($data['warnings']) ? $data['warnings'] : []);
        $route->setWaypointOrders(isset($data['waypoint_order']) ? $data['waypoint_order'] : []);

        $route->setBound(new Bound(
            new Coordinate(
                $data['bounds']['southwest']['lat'],
                $data['bounds']['southwest']['lng']
            ),
            new Coordinate(
                $data['bounds']['northeast']['lat'],
                $data['bounds']['northeast']['lng']
            )
        ));

        return $route;
    }

    /**
     * @param mixed[] $data
     *
     * @return DirectionsLeg[]
     */
    private function buildLegs(array $data)
    {
        $legs = [];
        foreach ($data as $item) {
            $legs[] = $this->buildLeg($item);
        }

        return $legs;
    }

    /**
     * @param mixed[] $data
     *
     * @return DirectionsLeg
     */
    private function buildLeg(array $data)
    {
        $leg = new DirectionsLeg();
        $leg->setDistance(new Distance($data['distance']['text'], $data['distance']['value']));
        $leg->setDuration(new Duration($data['duration']['text'], $data['duration']['value']));
        $leg->setEndAddress($data['end_address']);
        $leg->setEndLocation(new Coordinate($data['end_location']['lat'], $data['end_location']['lng']));
        $leg->setStartAddress($data['start_address']);
        $leg->setStartLocation(new Coordinate($data['start_location']['lat'], $data['start_location']['lng']));
        $leg->setSteps($this->buildSteps($data['steps']));
        $leg->setViaWaypoints(isset($data['via_waypoint']) ? $data['via_waypoint'] : []);

        return $leg;
    }

    /**
     * @param mixed[] $data
     *
     * @return DirectionsStep[]
     */
    private function buildSteps(array $data)
    {
        $steps = [];
        foreach ($data as $item) {
            $steps[] = $this->buildStep($item);
        }

        return $steps;
    }

    /**
     * @param mixed[] $data
     *
     * @return DirectionsStep
     */
    private function buildStep(array $data)
    {
        $step = new DirectionsStep();
        $step->setDistance(new Distance($data['distance']['text'], $data['distance']['value']));
        $step->setDuration(new Duration($data['duration']['text'], $data['duration']['value']));
        $step->setEndLocation(new Coordinate($data['end_location']['lat'], $data['end_location']['lng']));
        $step->setInstructions($data['html_instructions']);
        $step->setEncodedPolyline(new EncodedPolyline($data['polyline']['points']));
        $step->setStartLocation(new Coordinate($data['start_location']['lat'], $data['start_location']['lng']));
        $step->setTravelMode($data['travel_mode']);

        return $step;
    }
}