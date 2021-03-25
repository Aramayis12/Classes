<?php

namespace app\controllers;

use Yii;
use app\models\Movies;
use app\models\ContactUs;
use app\models\ShowsEpisodes;
use app\services\DeviceServices;
use app\models\HomePageData;
use yii\helpers\ArrayHelper;
use app\models\Collection;
use yii\web\Response;
use yii\widgets\ActiveForm;

class HomeController extends \yii\web\Controller
{
    /**
     * @inheritdoc
     */
    public function beforeAction($action)
    {            
        if ($action->id == 'slider-ajax') {
            $this->enableCsrfValidation = false;
        }

        return parent::beforeAction($action);
    }

    public function actionIndex()
    {
        $moviesModel   = new Movies();

	$watchings = $moviesModel->getLatestWatchings();
        $watchings = $watchings->limit(9)->all();

        // Get first two sliders
        $homePageData = HomePageData::find()
            ->orderBy([
                'position' => SORT_ASC
            ])
            ->limit(2)
            ->all();

        $data = ArrayHelper::toArray($homePageData, [
            'app\models\HomePageData' => [
                'id',
                'title',
                'code',
                'icon',
                'section_background',
                'items' => function($model){
                    $movies = [];
                    switch ($model->code) {
                        case "featured_movies":
                            $moviesModel   = new Movies();
                            $movies = $moviesModel->getFeaturedMovies();
                            break;
                        case "random_movies":
                            $moviesModel   = new Movies();
                            $platform      = DeviceServices::getDevice();
                            $randomLimit   = ($platform == 'desktop') ? 9 : 12;
                            $movies = $moviesModel->getRandomMovies($randomLimit);
                            break;
                        case "latest_movies_added":
                            $moviesModel   = new Movies();
                            $movies = $moviesModel->getLatestMoviesAdded(20);
                            break;
                        case "latest_episodes_added":
                            $episodesModel = new ShowsEpisodes();
                            $movies = $episodesModel->getLatestEpisodesAdded(20);
                            break;
                        case "oscar_winnings":
                            // Get Collection ID with slug
                            $collection = Collection::find()
                                ->select('collection_id')
                                ->where([
                                    'slug' => Collection::OSCAR_WINNINGS
                                ])->one();

                            $collection_id = $collection->collection_id;

                            $movies = $model->getCollectionMovies($collection_id);
                            break;
                        case "netflix_originals":
                            // Get Collection ID with slug
                            $collection = Collection::find()
                                ->select('collection_id')
                                ->where([
                                    'slug' => Collection::NETFLIX_ORIGINAL_MOVIES
                                ])->one();

                            $collection_id = $collection->collection_id;

                            $movies = $model->getCollectionMovies($collection_id);
                            break;
                        case "dc_universe_collection":
                            // Get Collection ID with slug
                            $collection = Collection::find()
                                ->select('collection_id')
                                ->where([
                                    'slug' => Collection::DC_UNIVERSE_COLLECTION
                                ])->one();

                            $collection_id = $collection->collection_id;

                            $movies = $model->getCollectionMovies($collection_id);
                            break;
                        case "marvel_collection":
                            // Get Collection ID with slug
                            $collection = Collection::find()
                                ->select('collection_id')
                                ->where([
                                    'slug' => Collection::MARVEL_COLLECTION
                                ])->one();

                            $collection_id = $collection->collection_id;

                            $movies = $model->getCollectionMovies($collection_id);
                            break;
                        case "100_highest_rated_animated_films":
                            // Get Collection ID with slug
                            $collection = Collection::find()
                                ->select('collection_id')
                                ->where([
                                    'slug' => Collection::HIGHEST_RATED_ANIMATED_FILMS
                                ])->one();

                            $collection_id = $collection->collection_id;

                            $movies = $model->getCollectionMovies($collection_id);
                            break;
                        case "imdb_top_250":
                            // Get Collection ID with slug
                            $collection = Collection::find()
                                ->select('collection_id')
                                ->where([
                                    'slug' => Collection::IMDB_TOP
                                ])->one();

                            $collection_id = $collection->collection_id;

                            $movies = $model->getCollectionMovies($collection_id);
                            break;
                    }

                    return $movies;
                }
            ],
        ]);

        return $this->render($this->getDeviceView('index'), [
            'template'              => 'index',
            'data'                  => $data,
            'latest_watchings'      => $watchings,
        ]);
    }

    public function actionStructure(){
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $homePageData = HomePageData::find()
            ->orderBy([
                'position' => SORT_ASC
            ])
            ->all();

        $data = ArrayHelper::toArray($homePageData, [
            'app\models\HomePageData' => [
                'id',
                'title',
                'code',
                'icon',
                'section_background',
                'items' => function($model){
                    $movies = [];
                    switch ($model->code) {
                        case "featured_movies":
                            $moviesModel   = new Movies();
                            $movies = $moviesModel->getFeaturedMovies();
                            break;
                        case "random_movies":
                            $moviesModel   = new Movies();
                            $platform      = DeviceServices::getDevice();
                            $randomLimit   = ($platform == 'desktop') ? 9 : 12;
                            $movies = $moviesModel->getRandomMovies($randomLimit);
                            break;
                        case "latest_movies_added":
                            $moviesModel   = new Movies();
                            $movies = $moviesModel->getLatestMoviesAdded(20);
                            break;
                        case "latest_episodes_added":
                            $episodesModel = new ShowsEpisodes();
                            $movies = $episodesModel->getLatestEpisodesAdded(20);
                            break;
                        case "oscar_winnings":
                            // Get Collection ID with slug
                            $collection = Collection::find()
                                ->select('collection_id')
                                ->where([
                                    'slug' => Collection::OSCAR_WINNINGS
                                ])->one();

                            $collection_id = $collection->collection_id;

                            $movies = $model->getCollectionMovies($collection_id);
                            break;
                        case "netflix_originals":
                            // Get Collection ID with slug
                            $collection = Collection::find()
                                ->select('collection_id')
                                ->where([
                                    'slug' => Collection::NETFLIX_ORIGINAL_MOVIES
                                ])->one();

                            $collection_id = $collection->collection_id;

                            $movies = $model->getCollectionMovies($collection_id);
                            break;
                        case "dc_universe_collection":
                            // Get Collection ID with slug
                            $collection = Collection::find()
                                ->select('collection_id')
                                ->where([
                                    'slug' => Collection::DC_UNIVERSE_COLLECTION
                                ])->one();

                            $collection_id = $collection->collection_id;

                            $movies = $model->getCollectionMovies($collection_id);
                            break;
                        case "marvel_collection":
                            // Get Collection ID with slug
                            $collection = Collection::find()
                                ->select('collection_id')
                                ->where([
                                    'slug' => Collection::MARVEL_COLLECTION
                                ])->one();

                            $collection_id = $collection->collection_id;

                            $movies = $model->getCollectionMovies($collection_id);
                            break;
                        case "100_highest_rated_animated_films":
                            // Get Collection ID with slug
                            $collection = Collection::find()
                                ->select('collection_id')
                                ->where([
                                    'slug' => Collection::HIGHEST_RATED_ANIMATED_FILMS
                                ])->one();

                            $collection_id = $collection->collection_id;

                            $movies = $model->getCollectionMovies($collection_id);
                            break;
                        case "imdb_top_250":
                            // Get Collection ID with slug
                            $collection = Collection::find()
                                ->select('collection_id')
                                ->where([
                                    'slug' => Collection::IMDB_TOP
                                ])->one();

                            $collection_id = $collection->collection_id;

                            $movies = $model->getCollectionMovies($collection_id);
                            break;
                    }

                    return $movies;
                }
            ],
        ]);
    
        return $data;
    }

    public function actionSliderAjax(){
        $data = json_decode(file_get_contents('php://input'), true);

        return $this->renderPartial($this->getDeviceView('slider-partial'), [
            'data' => $data
        ]);
    }

    public function actionContactUs(){
        $model = new ContactUs;

        $this->performAjaxValidation($model);
    }

    /**
     * @param array|Model $model
     *
     * @throws ExitException
     */
    protected function performAjaxValidation($model)
    {
        if (Yii::$app->request->isAjax && !Yii::$app->request->isPjax) {
            if ($model->load(Yii::$app->request->post())) {
                Yii::$app->response->format = Response::FORMAT_JSON;
                Yii::$app->response->data = ActiveForm::validate($model);

                if(empty(Yii::$app->response->data)){
                    // Save data in database

                    $email = env('APP_ADMIN_EMAIL');

                    // Send email
                    $mail = \Yii::$app->mailer->compose('contact', [
                        'userEmail' => $model->email,
                        'title' => $model->subject,
                        'message' => $model->content,
                    ])->setFrom([env('APP_ROBOT_EMAIL') => env('APP_ROBOT_CONTACT_NAME')])
                        ->setTo($email)
                        ->setReplyTo(env('APP_ROBOT_EMAIL'))
                        ->setSubject('Lookmovie Contact Form');

                    $mail->send();

                    $model->save(false);
                }
                Yii::$app->end();
            }
        }
    }

    private function getDeviceView($default)
    {
        $platform = DeviceServices::getDevice();
        $view     = ($platform == 'mobile') ? 'mobile/' . $default : $default;
        return $view;
    }
}
