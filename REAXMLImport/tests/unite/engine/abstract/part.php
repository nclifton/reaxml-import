<?php
/**
 * UNITE
 * The automated site restoration system
 *
 * @copyright Copyright (c)2009-2014 Nicholas K. Dionysopoulos
 * @license   GNU GPL version 3 or, at your option, any later version
 * @package   unite
 */

// Protection against direct access
defined('UNITE') or die('Restricted access');

/**
 * The superclass of all UNITE Engine parts. The "parts" are intelligent stateful
 * classes which perform a single procedure and have preparation, running and
 * finalization phases. The transition between phases is handled automatically by
 * this superclass' tick() final public method, which should be the ONLY public API
 * exposed to the rest of the UNITE Engine.
 */
abstract class UAbstractPart extends UAbstractObject
{
	/**
	 * @var array The global state
	 */
	protected $state = array();

	/**
	 * Indicates whether this part has finished its initialisation cycle
	 *
	 * @var boolean
	 */
	protected $isPrepared = false;

	/**
	 * Indicates whether this part has more work to do (it's in running state)
	 *
	 * @var boolean
	 */
	protected $isRunning = false;

	/**
	 * Indicates whether this part has finished its finalization cycle
	 *
	 * @var boolean
	 */
	protected $isFinished = false;

	/**
	 * Indicates whether this part has finished its run cycle
	 *
	 * @var boolean
	 */
	protected $hasRan = false;

	/**
	 * The name of the engine part (a.k.a. Domain), used in return table
	 * generation.
	 *
	 * @var string
	 */
	protected $active_domain = "";

	/**
	 * The step this engine part is in. Used verbatim in return table and
	 * should be set by the code in the _run() method.
	 *
	 * @var string
	 */
	protected $active_step = "";

	/**
	 * A more detailed description of the step this engine part is in. Used
	 * verbatim in return table and should be set by the code in the _run()
	 * method.
	 *
	 * @var string
	 */
	protected $active_substep = "";

	/**
	 * Any configuration variables, in the form of an array.
	 *
	 * @var array
	 */
	protected $_parametersArray = array();

	/** @var int Last reported warnings's position in array */
	private $warnings_pointer = -1;

	/** @var bool Should we log the step nesting? */
	protected $nest_logging = false;

	/**
	 * Runs the preparation for this part. Should set _isPrepared to true
	 */
	abstract protected function _prepare();

	/**
	 * Runs the finalisation process for this part. Should set _isFinished to true.
	 */
	abstract protected function _finalize();

	/**
	 * Runs the main functionality loop for this part. Upon calling,
	 * should set the _isRunning to true. When it finished, should set
	 * the _hasRan to true. If an error is encountered, setError should
	 * be used.
	 */
	abstract protected function _run();

	/**
	 * Sets the engine part's internal state, in an easy to use manner
	 *
	 * @param   string $state        One of init, prepared, running, postrun, finished, error
	 * @param   string $errorMessage The reported error message, should the state be set to error
	 *
	 * @return  void
	 */
	protected function setState($state = 'init', $errorMessage = 'Invalid setState argument')
	{
		switch ($state)
		{
			case 'init':
				$this->isPrepared = false;
				$this->isRunning = false;
				$this->isFinished = false;
				$this->hasRun = false;
				break;

			case 'prepared':
				$this->isPrepared = true;
				$this->isRunning = false;
				$this->isFinished = false;
				$this->hasRun = false;
				break;

			case 'running':
				$this->isPrepared = true;
				$this->isRunning = true;
				$this->isFinished = false;
				$this->hasRun = false;
				break;

			case 'postrun':
				$this->isPrepared = true;
				$this->isRunning = false;
				$this->isFinished = false;
				$this->hasRun = true;
				break;

			case 'finished':
				$this->isPrepared = true;
				$this->isRunning = false;
				$this->isFinished = true;
				$this->hasRun = false;
				break;

			case 'error':
			default:
				$this->setError($errorMessage);
				break;
		}
	}

	/**
	 * The public interface to an engine part. This method takes care for
	 * calling the correct method in order to perform the initialisation -
	 * run - finalisation cycle of operation and return a proper response array.
	 *
	 * @param    int $nesting Current nesting level
	 *
	 * @return   array  A Reponse Array
	 */
	final public function tick($nesting = 0)
	{
		// Call the right action method, depending on engine part state
		switch ($this->getState())
		{
			case "init":
				$this->_prepare();
				break;
			case "prepared":
				$this->_run();
				break;
			case "running":
				$this->_run();
				break;
			case "postrun":
				$this->_finalize();
				break;
		}

		// Send a Return Table back to the caller
		return $this->_makeReturnTable();
	}

	/**
	 * Returns a copy of the class's status array
	 *
	 * @return array
	 */
	public function getStatusArray()
	{
		return $this->_makeReturnTable();
	}

	/**
	 * Sends any kind of setup information to the engine part. Using this,
	 * we avoid passing parameters to the constructor of the class. These
	 * parameters should be passed as an indexed array and should be taken
	 * into account during the preparation process only. This function will
	 * set the error flag if it's called after the engine part is prepared.
	 *
	 * @param   array $parametersArray   The parameters to be passed to the
	 *                                   engine part (in/out).
	 * @param   array $globalState       The global state of the engine (in/out)
	 *
	 * @return  void
	 */
	final public function setup(&$parametersArray = array(), &$globalState = array())
	{
		if ($this->isPrepared)
		{
			$this->setState('error', "Can't modify configuration after the preparation of " . $this->active_domain);
		}
		else
		{
			$this->_parametersArray = $parametersArray;
			$this->state = $globalState;
		}
	}

	/**
	 * Returns the state of this engine part.
	 *
	 * @return  string  The state of this engine part. It can be one of
	 *                  error, init, prepared, running, postrun, finished.
	 */
	final public function getState()
	{
		if ($this->getError())
		{
			return "error";
		}

		if (!($this->isPrepared))
		{
			return "init";
		}

		if (!($this->isFinished) && !($this->isRunning) && !($this->hasRun) && ($this->isPrepared))
		{
			return "prepared";
		}

		if (!($this->isFinished) && $this->isRunning && !($this->hasRun))
		{
			return "running";
		}

		if (!($this->isFinished) && !($this->isRunning) && $this->hasRun)
		{
			return "postrun";
		}

		if ($this->isFinished)
		{
			return "finished";
		}
	}

	/**
	 * Constructs a Response Array based on the engine part's state.
	 *
	 * @return array The Response Array for the current state
	 */
	final protected function _makeReturnTable()
	{
		// Get a list of warnings
		$warnings = $this->getWarnings();
		// Report only new warnings if there is no warnings queue size
		if ($this->_warnings_queue_size == 0)
		{
			if (($this->warnings_pointer > 0) && ($this->warnings_pointer < (count($warnings))))
			{
				$warnings = array_slice($warnings, $this->warnings_pointer + 1);
				$this->warnings_pointer += count($warnings);
			}
			else
			{
				$this->warnings_pointer = count($warnings);
			}
		}

		$out = array(
			'HasRun'   => (!($this->isFinished)),
			'Domain'   => $this->active_domain,
			'Step'     => $this->active_step,
			'Substep'  => $this->active_substep,
			'Error'    => $this->getError(),
			'Warnings' => $warnings
		);

		return $out;
	}

	/**
	 * Set the current domain of the engine
	 *
	 * @param  string $new_domain The new domain
	 */
	final protected function setDomain($new_domain)
	{
		$this->active_domain = $new_domain;
	}

	/**
	 * Get the current active domain
	 *
	 * @return string
	 */
	final public function getDomain()
	{
		return $this->active_domain;
	}

	/**
	 * Set the current step of the engine
	 *
	 * @param  string $new_step The new step
	 */
	final protected function setStep($new_step)
	{
		$this->active_step = $new_step;
	}

	/**
	 * Get the current active step
	 *
	 * @return string
	 */
	final public function getStep()
	{
		return $this->active_step;
	}

	/**
	 * Set the current sub-step of the engine
	 *
	 * @param  string $new_substep The new sub-step
	 */
	final protected function setSubstep($new_substep)
	{
		$this->active_substep = $new_substep;
	}

	/**
	 * Get the current active sub-step
	 *
	 * @return string
	 */
	final public function getSubstep()
	{
		return $this->active_substep;
	}

	/**
	 * Set the current global state
	 *
	 * @param array $state The new global state
	 */
	final public function setGlobalState(&$state)
	{
		$this->state = $state;
	}

	/**
	 * Get the current global state
	 *
	 * @return array The global state
	 */
	final public function &getGlobalState()
	{
		return $this->state;
	}
}