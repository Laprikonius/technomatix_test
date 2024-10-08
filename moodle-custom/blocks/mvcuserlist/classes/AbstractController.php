<?php
namespace block_mvcuserlist;
/**
 * Example:
 * class Controller extends AbstractController{}
 * Controller::getInstance($action)->execute();
 */
abstract class AbstractController
{
    /**
     * @return array [
     *      'exampleActionName1' => \namespace\actionClass1,
     *      'exampleActionName2' => \namespace\actionClass2,
     *  ];
     */
    abstract public static function actions();

    /** @var AbstractAction */
    protected $action;

    /**
     * @throws \Exception
     */
    public static function getInstance(string $action): AbstractController
    {
        return new static($action);
    }

    /**
     * @throws \Exception
     */
    public function __construct(string $action)
    {
        $actions = static::actions();
        if (!isset($actions[$action])) {
            throw new \RuntimeException('Error: incorrect action.');
        }

        /** @var AbstractAction $actionClass */
        $actionClass = $actions[$action];

        $this->action = $actionClass::getInstance();
    }

    /**
     * @throws \Exception
     */
    public function execute()
    {
        $this->validateAction();

        return $this->action->execute();
    }

    protected function validateAction()
    {
        if (!$this->action) {
            throw new \Exception('Action not set.');
        }

        if (!($this->action instanceof AbstractAction)) {
            throw new \Exception('Action does not exist.');
        }
    }
}