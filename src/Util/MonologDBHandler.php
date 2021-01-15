<?php

namespace App\Util;

use App\Entity\Log;
use Doctrine\ORM\EntityManagerInterface;
use Monolog\Handler\AbstractProcessingHandler;
use Monolog\Logger;

class MonologDBHandler extends AbstractProcessingHandler
{
    /**
     * @var EntityManagerInterface
     */
    protected $em;

    public function __construct(EntityManagerInterface $em, $level = Logger::ERROR, $bubble = true)
    {
        $this->em = $em;
        parent::__construct($level, $bubble);
    }

    /**
     * Called when writing to our database
     * @param array $record
     */
    protected function write(array $record)
    {
        try {
            $logEntry = new Log();
            $logEntry->setMessage($record['message']);
            $logEntry->setLevel($record['level']);
            $logEntry->setLevelName($record['level_name']);

            if (is_array($record['extra'])) {
                $logEntry->setExtra($record['extra']);
            } else {
                $logEntry->setExtra([]);
            }

            if (is_array($record['context'])) {
                $logEntry->setContext($record['context']);
            } else {
                $logEntry->setContext([]);
            }

            $this->em->persist($logEntry);
            $this->em->flush();
        } catch (\Exception $ex) {
            return [
                'status' => 'failed',
                'error' => $ex->getMessage()
            ];
        }
    }
}
