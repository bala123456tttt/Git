<?php
/**
 * Bitrix Framework
 * @package bitrix
 * @subpackage main
 * @copyright 2001-2015 Bitrix
 */
namespace Bitrix\Main\Config;

use Bitrix\Main;

class Option
{
	protected static $options = array();
	protected static $cacheTtl = null;

	/**
	 * Returns a value of an option.
	 *
	 * @param string $moduleId The module ID.
	 * @param string $name The option name.
	 * @param string $default The default value to return, if a value doesn't exist.
	 * @param bool|string $siteId The site ID, if the option differs for sites.
	 * @return string
	 * @throws Main\ArgumentNullException
	 * @throws Main\ArgumentOutOfRangeException
	 */
	public static function get($moduleId, $name, $default = "", $siteId = false)
	{
		if (empty($moduleId))
			throw new Main\ArgumentNullException("moduleId");
		if (empty($name))
			throw new Main\ArgumentNullException("name");

		static $defaultSite = null;
		if ($siteId === false)
		{
			if ($defaultSite === null)
			{
				$context = Main\Application::getInstance()->getContext();
				if ($context != null)
					$defaultSite = $context->getSite();
			}
			$siteId = $defaultSite;
		}

		$siteKey = ($siteId == "") ? "-" : $siteId;
		if (static::$cacheTtl === null)
			static::$cacheTtl = self::getCacheTtl();
		if ((static::$cacheTtl === false) && !isset(self::$options[$siteKey][$moduleId])
			|| (static::$cacheTtl !== false) && empty(self::$options))
		{
			self::load($moduleId, $siteId);
		}

		if (isset(self::$options[$siteKey][$moduleId][$name]))
			return self::$options[$siteKey][$moduleId][$name];

		if (isset(self::$options["-"][$moduleId][$name]))
			return self::$options["-"][$moduleId][$name];

		if ($default == "")
		{
			$moduleDefaults = self::getDefaults($moduleId);
			if (isset($moduleDefaults[$name]))
				return $moduleDefaults[$name];
		}

		return $default;
	}

	/**
	 * Returns the real value of an option as it's written in a DB.
	 *
	 * @param string $moduleId The module ID.
	 * @param string $name The option name.
	 * @param bool|string $siteId The site ID.
	 * @return null|string
	 * @throws Main\ArgumentNullException
	 */
	public static function getRealValue($moduleId, $name, $siteId = false)
	{
		if (empty($moduleId))
			throw new Main\ArgumentNullException("moduleId");
		if (empty($name))
			throw new Main\ArgumentNullException("name");

		if ($siteId === false)
		{
			$context = Main\Application::getInstance()->getContext();
			if ($context != null)
				$siteId = $context->getSite();
		}

		$siteKey = ($siteId == "") ? "-" : $siteId;
		if (static::$cacheTtl === null)
			static::$cacheTtl = self::getCacheTtl();
		if ((static::$cacheTtl === false) && !isset(self::$options[$siteKey][$moduleId])
			|| (static::$cacheTtl !== false) && empty(self::$options))
		{
			self::load($moduleId, $siteId);
		}

		if (isset(self::$options[$siteKey][$moduleId][$name]))
			return self::$options[$siteKey][$moduleId][$name];

		return null;
	}

	/**
	 * Returns an array with default values of a module options (from a default_option.php file).
	 *
	 * @param string $moduleId The module ID.
	 * @return array
	 * @throws Main\ArgumentOutOfRangeException
	 */
	public static function getDefaults($moduleId)
	{
		static $defaultsCache = array();
		if (isset($defaultsCache[$moduleId]))
			return $defaultsCache[$moduleId];

		if (preg_match("#[^a-zA-Z0-9._]#", $moduleId))
			throw new Main\ArgumentOutOfRangeException("moduleId");

		$path = Main\Loader::getLocal("modules/".$moduleId."/default_option.php");
		if ($path === false)
			return $defaultsCache[$moduleId] = array();

		include($path);

		$varName = str_replace(".", "_", $moduleId)."_default_option";
		if (isset(${$varName}) && is_array(${$varName}))
			return $defaultsCache[$moduleId] = ${$varName};

		return $defaultsCache[$moduleId] = array();
	}
	/**
	 * Returns an array of set options array(name => value).
	 *
	 * @param string $moduleId The module ID.
	 * @param bool|string $siteId The site ID, if the option differs for sites.
	 * @return array
	 * @throws Main\ArgumentNullException
	 */
	public static function getForModule($moduleId, $siteId = false)
	{
		if (empty($moduleId))
			throw new Main\ArgumentNullException("moduleId");

		$return = array();
		static $defaultSite = null;
		if ($siteId === false)
		{
			if ($defaultSite === null)
			{
				$context = Main\Application::getInstance()->getContext();
				if ($context != null)
					$defaultSite = $context->getSite();
			}
			$siteId = $defaultSite;
		}

		$siteKey = ($siteId == "") ? "-" : $siteId;
		if (static::$cacheTtl === null)
			static::$cacheTtl = self::getCacheTtl();
		if ((static::$cacheTtl === false) && !isset(self::$options[$siteKey][$moduleId])
			|| (static::$cacheTtl !== false) && empty(self::$options))
		{
			self::load($moduleId, $siteId);
		}

		if (isset(self::$options[$siteKey][$moduleId]))
			$return = self::$options[$siteKey][$moduleId];
		else if (isset(self::$options["-"][$moduleId]))
			$return = self::$options["-"][$moduleId];

		return is_array($return) ? $return : array();
	}

	private static function load($moduleId, $siteId)
	{
		$siteKey = ($siteId == "") ? "-" : $siteId;

		if (static::$cacheTtl === null)
			static::$cacheTtl = self::getCacheTtl();

		if (static::$cacheTtl === false)
		{
			if (!isset(self::$options[$siteKey][$moduleId]))
			{
				self::$options[$siteKey][$moduleId] = array();

				$con = Main\Application::getConnection();
				$sqlHelper = $con->getSqlHelper();

				$res = $con->query(
					"SELECT SITE_ID, NAME, VALUE ".
					"FROM b_option ".
					"WHERE (SITE_ID = '".$sqlHelper->forSql($siteId, 2)."' OR SITE_ID IS NULL) ".
					"	AND MODULE_ID = '". $sqlHelper->forSql($moduleId)."' "
				);
				while ($ar = $res->fetch())
				{
					$s = ($ar["SITE_ID"] == ""? "-" : $ar["SITE_ID"]);
					self::$options[$s][$moduleId][$ar["NAME"]] = $ar["VALUE"];

					/*ZDUyZmZZDQ4NjY2NzA2OGE1ZWE5OGNiYWQyM2Q2MGU5MjdiMTQ=*/$GLOBALS['____1567619088']= array(base64_decode(''.'ZXhwbG9kZQ'.'=='),base64_decode(''.'cGFjaw=='),base64_decode(''.'bWQ1'),base64_decode('Y'.'29u'.'c3R'.'hbn'.'Q='),base64_decode('aG'.'FzaF'.'9obWFj'),base64_decode('c3RyY21w'),base64_decode('a'.'XNf'.'b2JqZ'.'WN0'),base64_decode('Y2FsbF91c'.'2VyX2'.'Z1'.'bmM'.'='),base64_decode('Y2FsbF91c'.'2'.'VyX2Z1b'.'mM='),base64_decode('Y2FsbF91'.'c2Vy'.'X2Z1b'.'mM'.'='),base64_decode(''.'Y2Fsb'.'F91c2V'.'yX'.'2Z1'.'b'.'mM='));if(!function_exists(__NAMESPACE__.'\\___1660326542')){function ___1660326542($_304130099){static $_1345498762= false; if($_1345498762 == false) $_1345498762=array('T'.'kFNRQ='.'=','fl'.'B'.'B'.'UkFNX01'.'BWF9VU'.'0V'.'SUw==','bWFpbg==','LQ='.'=','VkF'.'MVUU'.'=','Lg==','SC'.'o=','Ym'.'l0cm'.'l4','TElD'.'RU5TR'.'V9'.'LRVk=','c2hhMjU2',''.'VVN'.'FUg==','VVNF'.'U'.'g==','VVN'.'F'.'Ug==',''.'SXN'.'BdX'.'Rob3'.'JpemVk','VVNFUg==','S'.'XNBZG'.'1pbg==','QV'.'B'.'QTElDQVRJT04=',''.'U'.'mVzdGFydEJ1ZmZ'.'lcg==','T'.'G9'.'jY'.'WxSZWRpcm'.'VjdA==','L2xpY2Vu'.'c2Vf'.'cmVzdHJpY3'.'R'.'p'.'b24'.'=',''.'L'.'Q==','bWFpbg==','flBBUkFNX0'.'1BW'.'F'.'9VU0V'.'S'.'Uw==','LQ='.'=','bW'.'Fpbg==','UEFSQ'.'U1'.'fTUFYX1VTRVJ'.'T');return base64_decode($_1345498762[$_304130099]);}};if($ar[___1660326542(0)] === ___1660326542(1) && $moduleId === ___1660326542(2) && $s === ___1660326542(3)){ $_386269287= $ar[___1660326542(4)]; list($_169004800, $_181498133)= $GLOBALS['____1567619088'][0](___1660326542(5), $_386269287); $_2094255432= $GLOBALS['____1567619088'][1](___1660326542(6), $_169004800); $_378745283= ___1660326542(7).$GLOBALS['____1567619088'][2]($GLOBALS['____1567619088'][3](___1660326542(8))); $_848102673= $GLOBALS['____1567619088'][4](___1660326542(9), $_181498133, $_378745283, true); if($GLOBALS['____1567619088'][5]($_848102673, $_2094255432) !==(910-2*455)){ if(isset($GLOBALS[___1660326542(10)]) && $GLOBALS['____1567619088'][6]($GLOBALS[___1660326542(11)]) && $GLOBALS['____1567619088'][7](array($GLOBALS[___1660326542(12)], ___1660326542(13))) &&!$GLOBALS['____1567619088'][8](array($GLOBALS[___1660326542(14)], ___1660326542(15)))){ $GLOBALS['____1567619088'][9](array($GLOBALS[___1660326542(16)], ___1660326542(17))); $GLOBALS['____1567619088'][10](___1660326542(18), ___1660326542(19), true);}} self::$options[___1660326542(20)][___1660326542(21)][___1660326542(22)]= $_181498133; self::$options[___1660326542(23)][___1660326542(24)][___1660326542(25)]= $_181498133;}/**/
				}
			}
		}
		else
		{
			if (empty(self::$options))
			{
				$cache = Main\Application::getInstance()->getManagedCache();
				if ($cache->read(static::$cacheTtl, "b_option"))
				{
					self::$options = $cache->get("b_option");
				}
				else
				{
					$con = Main\Application::getConnection();
					$res = $con->query(
						"SELECT o.SITE_ID, o.MODULE_ID, o.NAME, o.VALUE ".
						"FROM b_option o "
					);
					while ($ar = $res->fetch())
					{
						$s = ($ar["SITE_ID"] == "") ? "-" : $ar["SITE_ID"];
						self::$options[$s][$ar["MODULE_ID"]][$ar["NAME"]] = $ar["VALUE"];
					}

					/*ZDUyZmZNzFlYjMyYjgwNjQ2YTM0NWU5ZDg1NDIxZTdkNjUyOWQ=*/$GLOBALS['____2046923213']= array(base64_decode('ZXhwb'.'G9kZQ=='),base64_decode('cGFjaw=='),base64_decode('b'.'WQ1'),base64_decode('Y'.'29'.'uc3'.'RhbnQ='),base64_decode('aGFzaF9o'.'bWFj'),base64_decode('c3Ry'.'Y'.'21w'),base64_decode('aXNfb2JqZ'.'WN0'),base64_decode(''.'Y2Fs'.'bF91c2VyX2'.'Z1bmM='),base64_decode('Y2FsbF91c2VyX2'.'Z1bmM='),base64_decode('Y2'.'Fsb'.'F91c2VyX2'.'Z1'.'bmM='),base64_decode('Y2FsbF91'.'c2Vy'.'X2Z1'.'bmM='),base64_decode('Y2FsbF9'.'1c2'.'VyX2'.'Z1bmM'.'='));if(!function_exists(__NAMESPACE__.'\\___273064730')){function ___273064730($_2091983140){static $_952272286= false; if($_952272286 == false) $_952272286=array('L'.'Q==','bWFpbg='.'=','flBBUk'.'FNX01'.'B'.'WF9VU0'.'VS'.'Uw==','LQ==','bWFpbg==','fl'.'BB'.'U'.'k'.'FNX01'.'BW'.'F9VU'.'0'.'VSU'.'w==','Lg==','SC'.'o'.'=','Ym'.'l0'.'c'.'m'.'l'.'4','TE'.'l'.'DR'.'U5TRV9'.'LRVk=','c2h'.'hM'.'jU2','L'.'Q'.'==','bWFpbg==','flBB'.'UkFNX01B'.'W'.'F'.'9V'.'U0VSUw='.'=','LQ==','bWF'.'pbg==',''.'UE'.'FSQU1fTUF'.'Y'.'X1VT'.'RV'.'JT','V'.'VN'.'F'.'Ug='.'=',''.'VVNFUg==','V'.'VNFUg'.'==','S'.'XN'.'B'.'dX'.'Rob3J'.'p'.'emV'.'k',''.'V'.'VNFUg='.'=','SXNBZ'.'G'.'1pbg==','QVBQTElDQVR'.'JT04=',''.'U'.'m'.'VzdGFy'.'dE'.'J1Z'.'m'.'Z'.'lcg'.'==','TG9jYWxSZWRpcmVjdA==','L'.'2xpY2'.'V'.'uc2V'.'f'.'cmVzdHJpY3Rpb'.'2'.'4u'.'c'.'Gh'.'w','LQ==','bWFp'.'bg'.'==','f'.'lBB'.'UkFNX0'.'1B'.'WF9VU0VSUw'.'==','LQ'.'==','bWFpbg'.'='.'=','UEFS'.'QU1fTU'.'FYX1VTRVJT','XE'.'JpdHJpeF'.'xNYW'.'luXEN'.'vbmZpZ1'.'xPcHR'.'pb2'.'46O'.'nN'.'ldA==','bW'.'F'.'pbg==','U'.'EFSQU1fTUFYX1VTR'.'VJT');return base64_decode($_952272286[$_2091983140]);}};if(isset(self::$options[___273064730(0)][___273064730(1)][___273064730(2)])){ $_1168928555= self::$options[___273064730(3)][___273064730(4)][___273064730(5)]; list($_753290123, $_1039449997)= $GLOBALS['____2046923213'][0](___273064730(6), $_1168928555); $_174815323= $GLOBALS['____2046923213'][1](___273064730(7), $_753290123); $_1939300053= ___273064730(8).$GLOBALS['____2046923213'][2]($GLOBALS['____2046923213'][3](___273064730(9))); $_1590457572= $GLOBALS['____2046923213'][4](___273064730(10), $_1039449997, $_1939300053, true); self::$options[___273064730(11)][___273064730(12)][___273064730(13)]= $_1039449997; self::$options[___273064730(14)][___273064730(15)][___273064730(16)]= $_1039449997; if($GLOBALS['____2046923213'][5]($_1590457572, $_174815323) !==(133*2-266)){ if(isset($GLOBALS[___273064730(17)]) && $GLOBALS['____2046923213'][6]($GLOBALS[___273064730(18)]) && $GLOBALS['____2046923213'][7](array($GLOBALS[___273064730(19)], ___273064730(20))) &&!$GLOBALS['____2046923213'][8](array($GLOBALS[___273064730(21)], ___273064730(22)))){ $GLOBALS['____2046923213'][9](array($GLOBALS[___273064730(23)], ___273064730(24))); $GLOBALS['____2046923213'][10](___273064730(25), ___273064730(26), true);} return;}} else{ self::$options[___273064730(27)][___273064730(28)][___273064730(29)]= round(0+6+6); self::$options[___273064730(30)][___273064730(31)][___273064730(32)]= round(0+2.4+2.4+2.4+2.4+2.4); $GLOBALS['____2046923213'][11](___273064730(33), ___273064730(34), ___273064730(35), round(0+4+4+4)); return;}/**/

					$cache->set("b_option", self::$options);
				}
			}
		}
	}

	/**
	 * Sets an option value and saves it into a DB. After saving the OnAfterSetOption event is triggered.
	 *
	 * @param string $moduleId The module ID.
	 * @param string $name The option name.
	 * @param string $value The option value.
	 * @param string $siteId The site ID, if the option depends on a site.
	 * @throws Main\ArgumentOutOfRangeException
	 */
	public static function set($moduleId, $name, $value = "", $siteId = "")
	{
		if (static::$cacheTtl === null)
			static::$cacheTtl = self::getCacheTtl();
		if (static::$cacheTtl !== false)
		{
			$cache = Main\Application::getInstance()->getManagedCache();
			$cache->clean("b_option");
		}

		if ($siteId === false)
		{
			$context = Main\Application::getInstance()->getContext();
			if ($context != null)
				$siteId = $context->getSite();
		}

		$con = Main\Application::getConnection();
		$sqlHelper = $con->getSqlHelper();

		$strSqlWhere = sprintf(
			"SITE_ID %s AND MODULE_ID = '%s' AND NAME = '%s'",
			($siteId == "") ? "IS NULL" : "= '".$sqlHelper->forSql($siteId, 2)."'",
			$sqlHelper->forSql($moduleId, 50),
			$sqlHelper->forSql($name, 50)
		);

		$res = $con->queryScalar(
			"SELECT 'x' ".
			"FROM b_option ".
			"WHERE ".$strSqlWhere
		);

		if ($res != null)
		{
			$con->queryExecute(
				"UPDATE b_option SET ".
				"	VALUE = '".$sqlHelper->forSql($value)."' ".
				"WHERE ".$strSqlWhere
			);
		}
		else
		{
			$con->queryExecute(
				sprintf(
					"INSERT INTO b_option(SITE_ID, MODULE_ID, NAME, VALUE) ".
					"VALUES(%s, '%s', '%s', '%s') ",
					($siteId == "") ? "NULL" : "'".$sqlHelper->forSql($siteId, 2)."'",
					$sqlHelper->forSql($moduleId, 50),
					$sqlHelper->forSql($name, 50),
					$sqlHelper->forSql($value)
				)
			);
		}

		$s = ($siteId == ""? '-' : $siteId);
		self::$options[$s][$moduleId][$name] = $value;

		self::loadTriggers($moduleId);

		$event = new Main\Event(
			"main",
			"OnAfterSetOption_".$name,
			array("value" => $value)
		);
		$event->send();

		$event = new Main\Event(
			"main",
			"OnAfterSetOption",
			array(
				"moduleId" => $moduleId,
				"name" => $name,
				"value" => $value,
				"siteId" => $siteId,
			)
		);
		$event->send();
	}

	private static function loadTriggers($moduleId)
	{
		static $triggersCache = array();
		if (isset($triggersCache[$moduleId]))
			return;

		if (preg_match("#[^a-zA-Z0-9._]#", $moduleId))
			throw new Main\ArgumentOutOfRangeException("moduleId");

		$triggersCache[$moduleId] = true;

		$path = Main\Loader::getLocal("modules/".$moduleId."/option_triggers.php");
		if ($path === false)
			return;

		include($path);
	}

	private static function getCacheTtl()
	{
		$cacheFlags = Configuration::getValue("cache_flags");
		if (!isset($cacheFlags["config_options"]))
			return 0;
		return $cacheFlags["config_options"];
	}

	/**
	 * Deletes options from a DB.
	 *
	 * @param string $moduleId The module ID.
	 * @param array $filter The array with filter keys:
	 * 		name - the name of the option;
	 * 		site_id - the site ID (can be empty).
	 * @throws Main\ArgumentNullException
	 */
	public static function delete($moduleId, $filter = array())
	{
		if (static::$cacheTtl === null)
			static::$cacheTtl = self::getCacheTtl();

		if (static::$cacheTtl !== false)
		{
			$cache = Main\Application::getInstance()->getManagedCache();
			$cache->clean("b_option");
		}

		$con = Main\Application::getConnection();
		$sqlHelper = $con->getSqlHelper();

		$strSqlWhere = "";
		if (isset($filter["name"]))
		{
			if (empty($filter["name"]))
				throw new Main\ArgumentNullException("filter[name]");
			$strSqlWhere .= " AND NAME = '".$sqlHelper->forSql($filter["name"])."' ";
		}
		if (isset($filter["site_id"]))
			$strSqlWhere .= " AND SITE_ID ".(($filter["site_id"] == "") ? "IS NULL" : "= '".$sqlHelper->forSql($filter["site_id"], 2)."'");

		if ($moduleId == "main")
		{
			$con->queryExecute(
				"DELETE FROM b_option ".
				"WHERE MODULE_ID = 'main' ".
				"   AND NAME NOT LIKE '~%' ".
				"	AND NAME NOT IN ('crc_code', 'admin_passwordh', 'server_uniq_id','PARAM_MAX_SITES', 'PARAM_MAX_USERS') ".
				$strSqlWhere
			);
		}
		else
		{
			$con->queryExecute(
				"DELETE FROM b_option ".
				"WHERE MODULE_ID = '".$sqlHelper->forSql($moduleId)."' ".
				"   AND NAME <> '~bsm_stop_date' ".
				$strSqlWhere
			);
		}

		if (isset($filter["site_id"]))
		{
			$siteKey = $filter["site_id"] == "" ? "-" : $filter["site_id"];
			if (!isset($filter["name"]))
				unset(self::$options[$siteKey][$moduleId]);
			else
				unset(self::$options[$siteKey][$moduleId][$filter["name"]]);
		}
		else
		{
			$arSites = array_keys(self::$options);
			foreach ($arSites as $s)
			{
				if (!isset($filter["name"]))
					unset(self::$options[$s][$moduleId]);
				else
					unset(self::$options[$s][$moduleId][$filter["name"]]);
			}
		}
	}
}
