<?php


namespace App\Rabbit;


use Doctrine\ORM\EntityManagerInterface;
use OldSound\RabbitMqBundle\RabbitMq\ConsumerInterface;
use PhpAmqpLib\Message\AMQPMessage;

class CheckPolicyConsumer implements ConsumerInterface
{
    private $entityManager;

    /**
     * CheckPolicyConsumer constructor.
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;

        gc_enable();
    }

    /**
     * @param AMQPMessage $msg
     * @return mixed|void
     */
    public function execute(AMQPMessage $msg)
    {
        $orderId = $msg->getBody();
        //1. беремо заявку з БД
        //2. завантажуємо/парсимо PDF
        //3. перевіряємо чи відповідає поліс нашій заявці з БД
    }

}