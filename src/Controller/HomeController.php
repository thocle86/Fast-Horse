<?php

/**
 * Created by PhpStorm.
 * User: aurelwcs
 * Date: 08/04/19
 * Time: 18:40
 */

namespace App\Controller;

use App\Model\APIManager;

class HomeController extends AbstractController
{
    /**
     * Display home page
     *
     * @return string
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function index()
    {
        $locations = (new APIManager())->getData();
        $countries = (new APIManager())->filterCountry($locations);
        return $this->twig->render('Home/index.html.twig', ["countries" => $countries]);
    }
    public function city()
    {
        $locations = (new APIManager())->getData();
        $countries = (new APIManager())->filterCountry($locations);
        $cities = (new APIManager())->filterCity($locations, $_POST["country"]);
        $countrySelected = $_POST["country"];
        return $this->twig->render(
            'Home/index.html.twig',
            ["countries" => $countries,
            "cities" => $cities,
            "countrySelected" => $countrySelected]
        );
    }

    public function horses()
    {
        $message = "";

        $locations = (new ApiManager())->getData();
        $horses = (new ApiManager())->numberHorsesCity($locations, $_POST['city']);
        $citySelected = $_POST['city'];

        if ($horses <= 0) {
            $message = "There are not good horse here, cow-boy!";

            return $this->twig->render(
                'Home/index.html.twig',
                ['message' => $message,
                'citySelected' => $citySelected]
            );
        } else {
            $message = "Good travel cowboy";
            return $this->twig->render(
                'Home/travel.html.twig',
                ['horses' => $horses,
                'message' => $message,
                'citySelected' => $citySelected]
            );
        }
    }
}
