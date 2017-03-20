<?php
namespace EvantSource;

class PhpAppendStore
{
    private $dataDir;

    public function __construct($dataDir)
    {
        $this->dataDir = $dataDir;
    }

    public function append(string $name, string $data, array $metadata, int $expectedVersion)
    {
        $version = $expectedVersion + 1;

        $time = microtime(true);

        if (false === strpos($time, '.')) {
            $time .= '.0000';
        }

        $when = \DateTimeImmutable::createFromFormat('U.u', $time);

        $row = [
            'type'    => $metadata['type'],
            'when' => $when->format('Y-m-d\TH:i:s.u'),
            'version' => $version,
            'payload' => $data,
        ];

        $this->write($name, $version, $row);
    }

    public function readRecords(string $name, int $afterVersion = 0, int $maxCount = null): array
    {
        $maxCount = $maxCount ? $maxCount : PHP_INT_MAX;

        $records = [];

        for ($i = $afterVersion + 1; $i <= $afterVersion + $maxCount; $i++) {

            if (!$record = $this->read($name, $i)) {
                break;
            }

            $records[] = $record;
        }

        return $records;
    }

    private function write(string $name, int $version, array $data)
    {
        $eventFile = $this->eventFile($name, $version);

        if (file_exists($eventFile)) {
            throw new \Exception('Concurrency issue');
        }

        if (!is_dir("{$this->dataDir}/{$name}")) {
            mkdir("{$this->dataDir}/{$name}");
        }

        $u = umask(0377);
        file_put_contents($eventFile, '<?php return ' . var_export($data, true) . ';');
        umask($u);
    }

    private function read($name, $version)
    {
        if (!file_exists($this->eventFile($name, $version))) {
            return false;

        }
        return include $this->eventFile($name, $version);
    }

    private function eventFile(string $name, int $version)
    {
        return "{$this->dataDir}/{$name}/{$version}.php";
    }
}
