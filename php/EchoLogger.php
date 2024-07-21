<?php

namespace Reydi;

use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel;

class EchoLogger implements LoggerInterface
{
    private $logLevels = [
        LogLevel::EMERGENCY => 'EMERGENCY',
        LogLevel::ALERT => 'ALERT',
        LogLevel::CRITICAL => 'CRITICAL',
        LogLevel::ERROR => 'ERROR',
        LogLevel::WARNING => 'WARNING',
        LogLevel::NOTICE => 'NOTICE',
        LogLevel::INFO => 'INFO',
        LogLevel::DEBUG => 'DEBUG',
    ];

    /**
     * Logs with an arbitrary level.
     *
     * @param mixed  $level
     * @param string $message
     * @param array  $context
     *
     * @return void
     */
    public function log($level, string|\Stringable $message, array $context = []): void
    {
        if (!isset($this->logLevels[$level])) {
            throw new \Psr\Log\InvalidArgumentException('Invalid log level: ' . $level);
        }

        $timestamp = date('Y-m-d H:i:s');
        $contextString = $this->contextToString($context);

        echo sprintf("[%s] %s: %s %s\n", $timestamp, $this->logLevels[$level], $message, $contextString);
    }

    /**
     * Converts the context array to a string.
     *
     * @param array $context
     *
     * @return string
     */
    private function contextToString(array $context)
    {
        if (empty($context)) {
            return '';
        }

        $replacements = [];
        foreach ($context as $key => $value) {
            if (is_null($value) || is_scalar($value) || (is_object($value) && method_exists($value, '__toString'))) {
                $replacements["{{$key}}"] = $value;
            } elseif (is_object($value)) {
                $replacements["{{$key}}"] = '[object ' . get_class($value) . ']';
            } else {
                $replacements["{{$key}}"] = '[' . gettype($value) . ']';
            }
        }

        return strtr(json_encode($context), $replacements);
    }

    public function emergency(string|\Stringable $message, array $context = []): void
    {
        $this->log(LogLevel::EMERGENCY, $message, $context);
    }

    public function alert(string|\Stringable $message, array $context = []): void
    {
        $this->log(LogLevel::ALERT, $message, $context);
    }

    public function critical(string|\Stringable $message, array $context = []): void
    {
        $this->log(LogLevel::CRITICAL, $message, $context);
    }

    public function error(string|\Stringable $message, array $context = []): void
    {
        $this->log(LogLevel::ERROR, $message, $context);
    }

    public function warning(string|\Stringable $message, array $context = []): void
    {
        $this->log(LogLevel::WARNING, $message, $context);
    }

    public function notice(string|\Stringable $message, array $context = []): void
    {
        $this->log(LogLevel::NOTICE, $message, $context);
    }

    public function info(string|\Stringable $message, array $context = []): void
    {
        $this->log(LogLevel::INFO, $message, $context);
    }

    public function debug(string|\Stringable $message, array $context = []): void
    {
        $this->log(LogLevel::DEBUG, $message, $context);
    }
}
