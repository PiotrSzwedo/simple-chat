<?php
abstract class Controller extends PageGenerator
{
    protected $description;
    protected MessageService $messageService;
    protected UserService $userService;
    protected SessionService $sessionService;
    protected ConvertService $convertService;

    public function __construct(
        string $action,
        array $parameters,
        UserService $userService,
        MessageService $messageService,
        SessionService $sessionService,
        ConvertService $convertService,
        DatabaseService $database
    ) {
        $this->userService = $userService;
        $this->messageService = $messageService;
        $this->sessionService = $sessionService;
        $this->convertService = $convertService;

        try {
            $descriptionData = $database->get("SELECT name, element FROM description");
            $this->description = $convertService->convertToStringByKey($descriptionData, "name", "element")[0];
        } catch (Exception $e) {
            $this->description = 'Default description';
            error_log($e->getMessage());
        }

        $this->induceFunction($action, $parameters);

        parent::__construct($database);
    }

    public function default()
    {
        $this->error404();
    }

    private function error404()
    {
        echo "Error 404";
    }

    private function induceFunction(string $action, array $parameters): void
    {
        if (empty($action)) {
            $this->default();
            return;
        }

        if (!method_exists($this, $action)) {
            $this->error404();
            return;
        }

        $reflectionMethod = new ReflectionMethod($this, $action);
        $requiredParams = $reflectionMethod->getNumberOfRequiredParameters();
        $allParams = $reflectionMethod->getNumberOfParameters();

        if (
            $reflectionMethod->isPublic() &&
            count($parameters) >= $requiredParams &&
            count($parameters) <= $allParams
        ) {
            $reflectionMethod->invoke($this, ...$parameters);
        } else {
            $this->error404();
        }
    }
}