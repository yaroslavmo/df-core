<?php
namespace DreamFactory\Core\Events;

use DreamFactory\Core\Components\ScriptHandler;
use DreamFactory\Core\Contracts\HttpStatusCodeInterface;
use DreamFactory\Core\Models\EventScript;
use Log;

class InterProcessApiEvent extends ApiEvent
{
    use ScriptHandler;

    public function handle()
    {
        Log::debug('API event handled: ' . $this->name);

        if ($script = $this->getEventScript($this->name)) {
            $data = $this->makeData();

            if (null !== $result = $this->handleEventScript($script, $data)) {
                return $this->handleEventScriptResult($script, $result);
            }
        }

        return true;
    }

    /**
     * @param EventScript $script
     * @param array       $event
     *
     * @return array|null
     * @throws
     * @throws \DreamFactory\Core\Events\Exceptions\ScriptException
     * @throws \DreamFactory\Core\Exceptions\InternalServerErrorException
     * @throws \DreamFactory\Core\Exceptions\RestException
     * @throws \DreamFactory\Core\Exceptions\ServiceUnavailableException
     */
    public function handleEventScript($script, &$event)
    {
        $result = $this->handleScript($script->name, $script->content, $script->type, $script->config, $event);

        // check for "return" results
        if (!empty($result)) {
            // could be formatted array or raw content
            if (is_array($result) && (isset($result['content']) || isset($result['status_code']))) {
                $result['response'] = $result;
            } else {
                // otherwise must be raw content, assumes 200
                $result['response']['content'] = $result;
                $result['response']['status_code'] = HttpStatusCodeInterface::HTTP_OK;
            }
        }

        return $result;
    }

    /**
     * @param EventScript $script
     * @param             $result
     *
     * @return bool
     */
    protected function handleEventScriptResult(
        /** @noinspection PhpUnusedParameterInspection */
        $script,
        $result
    ) {
        if (array_get($result, 'stop_propagation', false)) {
            Log::info('  * Propagation stopped by script.');

            return false;
        }

        return true;
    }
}
