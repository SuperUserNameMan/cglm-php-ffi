<?php

class GLM
{

	//----------------------------------------------------------------------------------
	// Const Definition
	//----------------------------------------------------------------------------------

	static $typeof_vec2;
	static $typeof_vec3;
	static $typeof_vec4;

	static $typeof_vec2s;
	static $typeof_vec3s;
	static $typeof_vec4s;

	//----------------------------------------------------------------------------------
	// FFI initialisation
	//----------------------------------------------------------------------------------

	public static $ffi;

	public static function GLM()
	{
		$cdef = __DIR__ . '/GLM.ffi.php.h';
		static::$ffi = FFI::load($cdef);

		static::$typeof_vec2  = FFI::typeof( static::$ffi->new("vec2" ) );
		static::$typeof_vec3  = FFI::typeof( static::$ffi->new("vec3" ) );
		static::$typeof_vec4  = FFI::typeof( static::$ffi->new("vec4" ) );

		static::$typeof_vec2s = FFI::typeof( static::$ffi->new("vec2s") );
		static::$typeof_vec3s = FFI::typeof( static::$ffi->new("vec3s") );
		static::$typeof_vec4s = FFI::typeof( static::$ffi->new("vec4s") );
	}


	public static function __callStatic( $method , $args )
	{
		$callable = [static::$ffi, 'glmc_'.$method];
		return $callable(...$args);
	}

	//----------------------------------------------------------------------------------
	// Helpers
	//----------------------------------------------------------------------------------


	static public function Vec2s( $S = null )
	{
		$V = static::$ffi->new("vec2s");

		if ( is_array( $S ) )
		{
			$V->x = $S[0] ?? 0.0 ;
			$V->y = $S[1] ?? 0.0 ;
		}
		else
		if ( is_object( $S ) )
		{
			switch( FFI::typeof( $S ) )
			{
				case static::$typeof_vec2s :
				case static::$typeof_vec3s :
				case static::$typeof_vec4s :
					$V->x = $S->x ;
					$V->y = $S->y ;
				break;

				default:
					exit("Error : unsupported parameter type for GLM::Vec2()");
			}
		}
		else
		{
			$S = floatval( $S );

			$V->x = $S ;
			$V->y = $S ;
		}

		return $V;
	}


	static public function Vec3s( $S = null )
	{
		$V = static::$ffi->new("vec3s");

		if ( is_array( $S ) )
		{
			$V->x = $S[0] ?? 0.0 ;
			$V->y = $S[1] ?? 0.0 ;
			$V->z = $S[2] ?? 0.0 ;
		}
		else
		if ( is_object( $S ) )
		{
			switch( FFI::typeof( $S ) )
			{
				case static::$typeof_vec2s :
					$V->x = $S->x ;
					$V->y = $S->y ;
					$V->z = 0.0   ;
				break;

				case static::$typeof_vec3s :
				case static::$typeof_vec4s :
					$V->x = $S->x ;
					$V->y = $S->y ;
					$V->z = $S->z ;
				break;

				default:
					exit("Error : unsupported parameter type for GLM::Vec3()");
			}
		}
		else
		{
			$S = floatval( $S );

			$V->x = $S ;
			$V->y = $S ;
			$V->z = $S ;
		}

		return $V;
	}

	static public function Vec4s( $S = null , $_w = 1.0 )
	{
		$V = static::$ffi->new("vec4s");

		if ( is_array( $S ) )
		{
			$V->x = $S[0] ?? 0.0 ;
			$V->y = $S[1] ?? 0.0 ;
			$V->z = $S[2] ?? 0.0 ;
			$V->w = $S[3] ?? $_w ;
		}
		else
		if ( is_object( $S ) )
		{
			switch( FFI::typeof( $S ) )
			{
				case static::$typeof_vec2s :
					$V->x = $S->x ;
					$V->y = $S->y ;
					$V->z = 0.0   ;
					$V->w = $_w   ;
				break;

				case static::$typeof_vec3s :
					$V->x = $S->x ;
					$V->y = $S->y ;
					$V->z = $S->z ;
					$V->w = $_w   ;
				break;

				case static::$typeof_vec4s :
					$V->x = $S->x ;
					$V->y = $S->y ;
					$V->z = $S->z ;
					$V->w = $S->w ;
				break;

				default:
					exit("Error : unsupported parameter type for GLM::Vec4()");
			}
		}
		else
		{
			$S = floatval( $S );

			$V->x = $S  ;
			$V->y = $S  ;
			$V->z = $S  ;
			$V->w = $_w ;
		}

		return $V;
	}

}
