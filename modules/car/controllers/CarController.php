<?php

declare(strict_types=1);

namespace app\modules\car\controllers;

use app\modules\car\application\services\CarService;
use app\modules\car\domain\exceptions\CarNotFoundException;
use app\modules\car\presentation\requests\CarCreateRequest;
use app\modules\car\presentation\requests\CarListRequest;
use app\modules\car\presentation\responses\CarErrorResponseFactory;
use app\modules\car\presentation\responses\CarResponseAssembler;
use Throwable;
use Yii;
use yii\filters\ContentNegotiator;
use yii\filters\VerbFilter;
use yii\rest\Controller;
use yii\web\Response;

final class CarController extends Controller
{
    public $enableCsrfValidation = false;

    public function __construct(
        string $id,
        $module,
        private readonly CarService $service,
        private readonly CarResponseAssembler $assembler,
        private readonly CarErrorResponseFactory $errors,
        array $config = []
    ) {
        parent::__construct($id, $module, $config);
    }

    public function behaviors(): array
    {
        $behaviors = parent::behaviors();
        unset($behaviors['authenticator'], $behaviors['rateLimiter']);

        $behaviors['contentNegotiator'] = [
            'class' => ContentNegotiator::class,
            'formats' => [
                'application/json' => Response::FORMAT_JSON,
            ],
        ];
        $behaviors['verbs'] = [
            'class' => VerbFilter::class,
            'actions' => [
                'create' => ['POST'],
                'view' => ['GET'],
                'list' => ['GET'],
            ],
        ];

        return $behaviors;
    }

    /**
     * @return array<string, mixed>
     */
    public function actionCreate(): array
    {
        $requestModel = new CarCreateRequest();
        $requestModel->load(Yii::$app->request->bodyParams, '');

        if (!$requestModel->validate()) {
            Yii::$app->response->statusCode = 422;
            return $this->errors->validation($requestModel->getErrors());
        }

        try {
            $car = $this->service->create($requestModel->toCommand());
        } catch (Throwable $exception) {
            Yii::error($exception);
            Yii::$app->response->statusCode = 500;
            return $this->errors->internal();
        }

        Yii::$app->response->statusCode = 201;
        return $this->assembler->toArray($car);
    }

    /**
     * @return array<string, mixed>
     */
    public function actionView(int $id): array
    {
        try {
            return $this->assembler->toArray($this->service->getById($id));
        } catch (CarNotFoundException) {
            Yii::$app->response->statusCode = 404;
            return $this->errors->notFound();
        }
    }

    /**
     * @return array<string, mixed>
     */
    public function actionList(): array
    {
        $requestModel = new CarListRequest();
        $requestModel->load(Yii::$app->request->get(), '');

        if (!$requestModel->validate()) {
            Yii::$app->response->statusCode = 400;
            return $this->errors->invalidPage();
        }

        $result = $this->service->list($requestModel->getPage(), 20);

        return [
            'items' => $this->assembler->many($result->getItems()),
            'pagination' => [
                'page' => $result->getPage(),
                'per_page' => $result->getPerPage(),
                'total' => $result->getTotal(),
                'pages' => $result->getPages(),
            ],
        ];
    }
}
