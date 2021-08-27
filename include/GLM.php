<?php

GLM::GLM(); // autoinit

/**
 *  The ` GLM ` class is used as a container for the glmc API.
 * 
 * This class only contains static members.
 * 
 * After inclusion, it has to be initialized with ` GLM::GLM(); `.
 * 
 * The full glmc API is bound to ` GLM::$ffi `.
 * 
 * THe ` GLM::__staticCall() ` magic method will automatically callback 
 * the appropriate ` GLM::$ffi->glmc_ ` function each time a non existant 
 * static method of ` GLM:: ` is called.
 * 
 * This means that each glmc function can be called with ` GLM:: `
 * as prefix (instead of ` glmc_ ` ).
 * 
 *  Ex : calling ` GLM::vec3_subadd( $a , $b , $dest ) ` will actually
 * call ` GLM::$ffi->glmc_vec3_subadd( ... ) `.
 * 
 *  Also, this means that it is possible to override each function of the
 * glmc API by adding a static method with the same name into` ` GLM:: `
 * 
 *  Ex : ` GLM::frustum_default() ` is an override method.
 * 
 * Overrides are used to simplify certain aspects of the C API.
 * 
 *  It is also possible to extend the original C API with new static 
 * methods.
 * 
 *  Ex : ` GLM::Vec3() ` can be used to create a new ` vec3 `CData, or 
 * to convert a PHP array into a ` vec3 ` CData.
 * 
 *  These overriding and extending `GLM::` methods are  called "helpers".
 * 
 * 
 *  Following the same principle as the ` GLM:: ` class, many other
 * classes have been added to re-encapsulate some parts of the glmc API.
 * 
 * 
 * Vectors have their own containers :
 * - Vec2:: 
 * - Vec3::
 * - Vec4::
 *  
 * Matrices have their own containers :
 * - Mat2::
 * - Mat3::
 * - Mat4::
 *
 * Aabb has its own container :
 * - Aabb::
 * 
 * 
 * Quaternion (aka "versor") has its own container :
 * - Quat:: 
 * 
 *
 * THe ` ::__staticCall ` magic method of each one of these class will
 * automatically callback the subset of the C API related to each.
 * 
 * Ex : ` Vec2::scale() ` will automatically calback ` glmc_vec2_scale() `.
 *
 * It's like if ` Vec2:: ` replace the prefix ` glmc_vec2_ `.
 * 
 * Also, to make life easier in PHP, each of their ` ::__staticCAll `
 * magic mathod will automatically return the last parameter passed
 * to each ` void ` function.
 * 
 * In most case, this last parameter is a `dest` or `source`  parameter.
 * 
 * With all these tiny improvments, it is thus possible to write :
 * 
 * ````
 * $V = Vec2::scale( Vec2::normalize( GLM::Vec2([ 123 , 456 ]) ), 10.0 );
 * 
 * ````
 * 
 * instead of :
 * 
 * ````
 * $V = FFI::new("float[2]");
 * $D = FFI::new("float[2]");
 * 
 * $V[0] = 123;
 * $V[1] = 456;
 * 
 * Vec2::normalize( $V );
 * Vec2::scale( $V , 10.0 , $D );
 * ````
 * 
 * However, regarding performance with PHP 8.0.8, even if JIT is enabled,
 * relying on ` __staticCall() ` will gives very poor performances.
 * For best performance, it is prefered to make direct use of the C API
 * using the ` GLM::$ffi->glmc_...() ` interface.
 * 
 */


class GLM
{

	//----------------------------------------------------------------------------------
	// Const Definition
	//----------------------------------------------------------------------------------

	const E         = 2.71828182845904523536028747135266250  ; /* e           */
	const LOG2E     = 1.44269504088896340735992468100189214  ; /* log2(e)     */
	const LOG10E    = 0.434294481903251827651128918916605082 ; /* log10(e)    */
	const LN2       = 0.693147180559945309417232121458176568 ; /* loge(2)     */
	const LN10      = 2.30258509299404568401799145468436421  ; /* loge(10)    */
	const PI        = 3.14159265358979323846264338327950288  ; /* pi          */
	const PI_2      = 1.57079632679489661923132169163975144  ; /* pi/2        */
	const PI_4      = 0.785398163397448309615660845819875721 ; /* pi/4        */
	const _1_PI     = 0.318309886183790671537767526745028724 ; /* 1/pi        */
	const _2_PI     = 0.636619772367581343075535053490057448 ; /* 2/pi        */
	const _2_SQRTPI = 1.12837916709551257389615890312154517  ; /* 2/sqrt(pi)  */
	const SQRT2     = 1.41421356237309504880168872420969808  ; /* sqrt(2)     */
	const SQRT1_2   = 0.707106781186547524400844362104849039 ; /* 1/sqrt(2)   */

	// --- FrustumCorners->v[ index ] :

	const LBN = 0 ; /* left  bottom near */
	const LTN = 1 ; /* left  top    near */
	const RTN = 2 ; /* right top    near */
	const RBN = 3 ; /* right bottom near */

	const LBF = 4 ; /* left  bottom far  */
	const LTF = 5 ; /* left  top    far  */
	const RTF = 6 ; /* right top    far  */
	const RBF = 7 ; /* right bottom far  */

	// --- FrustumPlanes->v[ index ]
	const LEFT   = 0 ;
	const RIGHT  = 1 ;
	const BOTTOM = 2 ;
	const TOP    = 3 ;
	const NEAR   = 4 ;
	const FAR    = 5 ;


	static $ffi_typeof_void_p; 

	static $ffi_typeof_vec2;
	static $ffi_typeof_vec3;
	static $ffi_typeof_vec4;
	
	static $ffi_typeof_mat2;
	static $ffi_typeof_mat3;
	static $ffi_typeof_mat4;
	
	static $ffi_typeof_aabb;
	
	static $ffi_typeof_quat; // aka versor
	
	static $ffi_typeof_FrustumPlanes;
	static $ffi_typeof_struct_LR_Planes;
	static $ffi_typeof_struct_TB_Planes;
	static $ffi_typeof_struct_NF_Planes; 
	
	static $ffi_typeof_FrustumCorners;
	static $ffi_typeof_PlaneCorners;
	

	//----------------------------------------------------------------------------------
	// FFI initialisation
	//----------------------------------------------------------------------------------

	public static $ffi;

	public static function GLM()
	{
		if ( static::$ffi ) 
		{ 
			debug_print_backtrace();
			exit("GLM::GLM() already init".PHP_EOL); 
		}
		
		$cdef = __DIR__ . '/GLM.ffi.php.h';
		
		$lib_dir = defined('FFI_LIB_DIR') ? FFI_LIB_DIR : 'lib' ;
		
		$slib = "./$lib_dir/libcglm.".PHP_SHLIB_SUFFIX;
		
		static::$ffi = FFI::cdef( file_get_contents( $cdef ) , $slib );
		
		static::$ffi_typeof_void_p= static::$ffi->type( "void*"  ); // TODO FIXME HACK : find a better way to FFI::type("void");

		static::$ffi_typeof_vec2  = static::$ffi->type( "vec2"   );
		static::$ffi_typeof_vec3  = static::$ffi->type( "vec3"   );
		static::$ffi_typeof_vec4  = static::$ffi->type( "vec4"   );
		
		static::$ffi_typeof_mat2  = static::$ffi->type( "mat2"   );
		static::$ffi_typeof_mat3  = static::$ffi->type( "mat3"   );
		static::$ffi_typeof_mat4  = static::$ffi->type( "mat4"   );
		
		static::$ffi_typeof_aabb  = static::$ffi->type( "Aabb");
		
		static::$ffi_typeof_quat  = static::$ffi->type( "versor" ); //!\ "versor" is how cglm calls "quaternion"
		
		static::$ffi_typeof_FrustumPlanes  = static::$ffi->type( "FrustumPlanes"  );
		static::$ffi_typeof_FrustumCorners = static::$ffi->type( "FrustumCorners" );
		static::$ffi_typeof_PlaneCorners   = static::$ffi->type( "PlaneCorners"  );
		
		static::$ffi_typeof_struct_LR_Planes = static::$ffi->type( "struct { float left,  right; }" );
		static::$ffi_typeof_struct_TB_Planes = static::$ffi->type( "struct { float  top, bottom; }" );
		static::$ffi_typeof_struct_NF_Planes = static::$ffi->type( "struct { float near,    far; }" );
	}


	public static function __callStatic( string $method , array $args ) : mixed
	{
		$callable = [static::$ffi, 'glmc_'.$method];
		return $callable(...$args);
	}
	
	//----------------------------------------------------------------------------------
	// Helpers
	//----------------------------------------------------------------------------------
	
	public static function ffi_returned_void( mixed $ffi_ret ) : bool
	{
		return 
			$ffi_ret instanceof FFI\CData
			&&
			FFI::typeof( FFI::addr( $ffi_ret ) ) == static::$ffi_typeof_void_p // TODO HACK FIXME : with PHP 8.0.8 I can't find a way to test directly against FFI::type("void");
			;
	}
	
	
	public static function Vec2( object|array|int $V=null ) : object
	{
		if ( static::is_Vec2( $V ) ) return $V ;
		
		return Vec2::new( $V );
	}
	
	public static function Vec3( object|array|int $V=null , float $_z=0.0 ) : object
	{
		if ( static::is_Vec3( $V ) ) return $V ;
		
		return Vec3::new( $V , $_z );
	}
	
	public static function Vec4( object|array|int $V=null , float $_w=1.0 ) : object
	{
		if ( static::is_Vec4( $V ) ) return $V ;
		
		return Vec4::new( $V , $_w );
	}
	
	public static function Mat2( object|array|int $M=null ) : object
	{
		if ( static::is_Mat2( $M ) ) return $M ;
		
		return Mat2::new( $M );
	}
	
	public static function Mat3( object|array|int $M=null ) : object
	{
		if ( static::is_Mat3( $M ) ) return $M ;
		
		return Mat3::new( $M );
	}
	
	public static function Mat4( object|array|int $M=null ) : object
	{
		if ( static::is_Mat4( $M ) ) return $M ;
		
		return Mat4::new( $M );
	}
	
	public static function Aabb( object|array|int $B=null ) : object
	{
		if ( static::is_Aabb( $B ) ) return $B ;
		
		return Aabb::new( $B );
	}
	
	public static function Quat( object|array|int $Q=null , float $_w = 0.0 ) : object
	{
		if ( static::is_Quat( $Q ) ) return $Q ;
		
		return Quat::new( $Q , $_w );
	}
	
	public static function is_Vec2( mixed $V ) : bool
	{
		return
			$V instanceof FFI\CData
			&&
			FFI::typeof( $V ) == static::$ffi_typeof_vec2
			;
	}
	
	public static function is_Vec3( mixed $V ) : bool
	{
		return
			$V instanceof FFI\CData
			&&
			FFI::typeof( $V ) == static::$ffi_typeof_vec3
			;
	}
	
	public static function is_Vec4( mixed $V ) : bool
	{
		return
			$V instanceof FFI\CData
			&&
			FFI::typeof( $V ) == static::$ffi_typeof_vec4
			;
	}
	
	public static function is_Mat2( mixed $V ) : bool
	{
		return
			$V instanceof FFI\CData
			&&
			FFI::typeof( $V ) == static::$ffi_typeof_mat2
			;
	}
	
	public static function is_Mat3( mixed $V ) : bool
	{
		return
			$V instanceof FFI\CData
			&&
			FFI::typeof( $V ) == static::$ffi_typeof_mat3
			;
	}
	
	public static function is_Mat4( mixed $V ) : bool
	{
		return
			$V instanceof FFI\CData
			&&
			FFI::typeof( $V ) == static::$ffi_typeof_mat4
			;
	}
	
	public static function is_Aabb( mixed $B ) : bool
	{
		return
			$V instanceof FFI\CData
			&&
			FFI::typeof( $B ) == static::$ffi_typeof_aabb
			;
	}
	
	public static function is_Quat( mixed $Q ) : bool
	{
		return
			$V instanceof FFI\CData
			&&
			FFI::typeof( $Q ) == static::$ffi_typeof_quat
			;
	}
	
	
	// affine.h --------------------------------
	
	public static function translate_make( object $M , object|array $V ) : object
	{
		$V = static::Vec3( $V );
		
		static::$ffi->glmc_translate_make( $M , $V );
		
		return $M;
	}
	
	public static function translate_to( object $M , object|array $V , object $D=null ) : object
	{
		$V = static::Vec3( $V );
		$D ??= static::Mat4();
		
		static::$ffi->glmc_translate_to( $M , $V , $D );
		
		return $D;
	}

	public static function translate( object $M , object|array $V ) : object
	{
		$V = static::Vec3( $V );
		
		static::$ffi->glmc_translate( $M , $V );
		
		return $M;
	}

	public static function translate_x( object $M , float $x ) : object
	{	
		static::$ffi->glmc_translate_x( $M , $x );
		
		return $M;
	}
	
	public static function translate_y( object $M , float $y ) : object
	{	
		static::$ffi->glmc_translate_y( $M , $y );
		
		return $M;
	}
	
	public static function translate_z( object $M , float $z ) : object
	{	
		static::$ffi->glmc_translate_x( $M , $z );
		
		return $M;
	}
	
	
	public static function scale_make( object $M , object|array $V ) : object
	{
		$M = static::Mat4( $M );		
		$V = static::Vec3( $V );
		
		static::$ffi->glmc_scale_make( $M , $V );
		
		return $M;
	}
	
	public static function scale_to( object $M , object|array $V , object $D=null ) : object
	{
		$V = static::Vec3( $V );
		$D ??= static::Mat4();
		
		static::$ffi->glmc_scale_to( $M , $V , $D );
		
		return $D;
	}

	public static function scale( object $M , object|array $V ) : object
	{
		$V = static::Vec3( $V );
		
		static::$ffi->glmc_scale( $M , $V );
		
		return $M;
	}

	public static function scale_uni( object $M , float $s ) : object
	{	
		static::$ffi->glmc_scale_uni( $M , $s );
		
		return $M;
	}
	
	public static function rotate_x( object $M , float $rad , object $D = null ) : object
	{
		$D ??= static::Mat4();
		
		static::$ffi->glmc_rotate_x( $M, $rad, $D );
		
		return $D;
	}
	
	public static function rotate_y( object $M , float $rad , object $D = null ) : object
	{
		$D ??= static::Mat4();
		
		static::$ffi->glmc_rotate_y( $M, $rad, $D );
		
		return $D;
	}
	
	public static function rotate_z( object $M , float $rad , object $D = null ) : object
	{
		$D ??= static::Mat4();
		
		static::$ffi->glmc_rotate_z( $M, $rad, $D );
		
		return $D;
	}

	public static function rotate_make( object $M , float $angle , object|array $V_axis ) : object
	{
		$V_axis = static::Vec3( $V_axis );
		
		static::$ffi->glmc_rotate_make( $M , $angle, $V_axis );
		
		return $M;
	}
	
	public static function rotate( object $M , float $angle , object|array $V_axis ) : object
	{
		$V_axis = static::Vec3( $V_axis );
		
		static::$ffi->glmc_rotate( $M , $angle, $V_axis );
		
		return $M;
	}

	public static function rotate_at( object $M , object|array $V_pivot , float $angle , object|array $V_axis ) : object
	{
		$V_pivot = static::Vec3( $V_pivot );
		$V_axis  = static::Vec3( $V_axis  );
	
		static::$ffi->glmc_rotate_at( $M , $V_pivot , $angle , $V_axis );
		
		return $M;
	}
	
	public static function decompose_scalev( object $M , object|array $V = null ) : object
	{
		$V = static::Vec3( $V );
		
		static::$ffi->glmc_decompose_scalev( $M , $V );
		
		return $V;
	}
	
	public static function decompose_rs( object $M , object $R = null , object $V = null ) : array
	{
		$R = static::Mat4( $R );
		$V = static::Vec3( $V );
		
		static::$ffi->glmc_decompose_rs( $M , $R , $V );
		
		return [ $R , $V ]; //!\\
	}

	public static function decompose( object $M , object $V_t=null , object $R=null , object $V_s=null ) : array
	{
		$V_t = static::Vec3( $V_t );
		$V_s = static::Vec3( $V_s );
		$R   = static::Mat4( $R   );
		
		static::$ffi->glmc_decompose( $M , $V_t , $R , $V_s );
		
		return [ $V_t , $R , $V_s ];
	}
	
	public static function mul( object $M1 , object $M2 , object $D=null ) : object
	{
		$D ??= static::Mat4();
		
		static::$ffi->glmc_mul( $M1 , $M2 , $D );
		
		return $D;
	}
	
	public static function mul_rot( object $M1 , object $M2 , object $D=null ) : object
	{
		$D ??= static::Mat4();
		
		static::$ffi->glmc_mul_rot( $M1 , $M2 , $D );
		
		return $D;
	}
	
	public static function inv_tr( object $M ) : object
	{
		static::$ffi->glmc_inv_tr( $M );
		
		return $M;
	}
	
	// cam.h -----------------------------------------------------------
	
	public static function frustum( float $left , float $right , float $bottom , float $top , float $nearZ , float $farZ , $D=null ) : object
	{
		$D = static::Mat4( $D );
		
		static::$ffi->glmc_frustum( $left , $right , $bottom , $top , $nearZ , $farZ , $D );
		
		return $D;
	}
	
	public static function ortho( float $left , float $right , float $bottom , float $top , float $nearZ , float $farZ , $D=null ) : object
	{
		$D = static::Mat4( $D );
		
		static::$ffi->glmc_ortho( $left , $right , $bottom , $top , $nearZ , $farZ , $D );
		
		return $D;
	}
	
	public static function ortho_aabb( object|array $B , object $D=null ) : object
	{
		$B = static::Aabb( $B );
		$D ??= static::Mat4();
		
		static::$ffi->glmc_ortho_aabb( $B->v , $D );
		
		return $D;
	}
	
	public static function ortho_aabb_p( object|array $B , float $padding , object $D=null ) : object
	{
		$B = static::Aabb( $B );
		$D ??= static::Mat4();
		
		static::$ffi->glmc_ortho_aabb_p( $B->v , $padding , $D );
		
		return $D;
	}
	
	public static function ortho_aabb_pz( object|array $B , float $padding , object $D=null ) : object
	{
		$B = static::Aabb( $B );
		$D ??= static::Mat4();
		
		static::$ffi->glmc_ortho_aabb_pz( $B->v , $padding , $D );
		
		return $D;
	}
	
	public static function ortho_default( float $aspect , object $D=null ) : object
	{
		$D ??= static::Mat4();
		
		static::$ffi->glmc_ortho_default( $aspect , $D );
		
		return $D;
	}
	
	public static function ortho_default_s( float $aspect , float $size , object $D=null ) : object
	{
		$D ??= static::Mat4();
		
		static::$ffi->glmc_ortho_default_s( $aspect , $size , $D );
		
		return $D;
	}
	
	public static function perspective( float $fovy , float $aspect , float $nearZ , float $farZ , object $D=null ) : object
	{
		$D ??= static::Mat4();
		
		static::$ffi->glmc_perspective( $fovy , $aspect , $nearZ , $farZ , $D );
		
		return $D;
	}
	
	public static function persp_move_far( object $P , float $deltaFar ) : object
	{	
		static::$ffi->glmc_persp_move_far( $P , deltaFar );
		
		return $P;
	}
	
	public static function perspective_default( float $aspect , object $D=null ) : object
	{
		$D ??= static::Mat4();
		
		static::$ffi->glmc_perspective_default( $aspect , $D );
		
		return $D;
	}
	
	public static function perspective_resize( object $P , float $aspect ) : object
	{		
		static::$ffi->glmc_perspective_resize( $aspect , $P ); //!\ C params inverted
		
		return $P;
	}
	
	public static function lookat( object|array $V_eye , object|array  $V_center , object|array $V_up , object $D=null ) : object
	{
		$V_eye    = static::Vec3( $V_eye    );
		$V_center = static::Vec3( $V_center );
		$V_up     = static::Vec3( $V_up     );
		
		$D ??= static::Mat4();
		
		static::$ffi->glmc_lookat( $V_eye , $V_center , $V_up , $D );
		
		return $D;
	}
	
	public static function look( object|array $V_eye , object|array $V_dir , object|array $V_up , object $D=null ) : object
	{
		$V_eye = static::Vec3( $V_eye );
		$V_dir = static::Vec3( $V_dir );
		$V_up  = static::Vec3( $V_up  );
		
		$D ??= static::Mat4();
		
		static::$ffi->glmc_look( $V_eye , $V_dir , $V_up , $D );
		
		return $D;
	}
	
	public static function look_anyup( object|array $V_eye , object|array $V_dir , object $D=null ) : object
	{
		$V_eye = static::Vec3( $V_eye );
		$V_dir = static::Vec3( $V_dir );
		
		$D ??= static::Mat4();
		
		static::$ffi->glmc_look_anyup( $V_eye , $V_dir , $D );
		
		return $D;
	}
	
	public static function persp_decomp( object $P ) : object
	{
		$F = static::$ffi->new( static::$ffi_typeof_FrustumPlanes );
		
		static::$ffi->glmc_persp_decomp( $P 
			, FFI::addr( $F->near   )
			, FFI::addr( $F->far    )
			, FFI::addr( $F->top    )
			, FFI::addr( $F->bottom ) 
			, FFI::addr( $F->left   )
			, FFI::addr( $F->right  )
		);
		
		return $F;
	}
	
	public static function persp_decomp_x( object $P ) : object
	{
		$F = static::$ffi->new( static::$ffi_typeof_struct_LR_Planes );
		
		static::$ffi->glmc_persp_decomp_x( $P 
			, FFI::addr( $F->left   )
			, FFI::addr( $F->right  )
		);
		
		return $F;
	}
	
	public static function persp_decomp_y( object $P ) : object
	{
		$F = static::$ffi->new( static::$ffi_typeof_struct_TB_Planes );
		
		static::$ffi->glmc_persp_decomp_y( $P 
			, FFI::addr( $F->top    )
			, FFI::addr( $F->bottom )
		);
		
		return $F;
	}
	
	public static function persp_decomp_z( object $P ) : object
	{
		$f = static::$ffi->new( static::$ffi_typeof_struct_NF_Planes );
		
		static::$ffi->glmc_persp_decomp_z( $P 
			, FFI::addr( $F->near )
			, FFI::addr( $F->far  )
		);
		
		return $F;
	}
	
	public static function persp_decomp_far( object $P ) : float
	{
		$f = static::$ffi->new("float[1]");
		
		static::$ffi->glmc_persp_decomp_far( $P , FFI::addr( $f[0] ) );
		
		return $f[0];
	}
	
	public static function persp_decomp_near( object $P ) : float
	{
		$f = static::$ffi->new("float[1]");
		
		static::$ffi->glmc_persp_decomp_near( $P , FFI::addr( $f[0] ) );
		
		return $f[0];
	}
	
	public static function persp_sizes( $P , float $fovy , $D=null ) : object
	{
		$D = static::Vec4( $D );
		
		static::$ffi->glmc_persp_sizes( $P , $fovy , $D );
		
		return $D;
	}
	
	// euler.h ---------------------------------------------------------
	
	public static function euler_angles( object $M , object|array $V ) : object
	{
		$V = static::Vec3( $V );
		
		static::$ffi->glmc_euler_angles( $M , $V );
		
		return $V;
	}
	
	public static function euler( object|array $V , object $M ) : object
	{
		$M = static::Mat4( $M );
		
		static::$ffi->glmc_euler( $V , $M );
		
		return $M;
	}
	
	public static function euler_xyz( object|array $V , object $M ) : object
	{
		$M = static::Mat4( $M );	
		static::$ffi->glmc_euler_xyz( $V , $M );
		return $M;
	}
	
	public static function euler_zyx( object|array $V , object $M ) : object
	{
		$M = static::Mat4( $M );
		static::$ffi->glmc_euler_zyx( $V , $M );
		return $M;
	}
	
	public static function euler_zxy( object|array $V , object $M ) : object
	{
		$M = static::Mat4( $M );	
		static::$ffi->glmc_euler_zxy( $V , $M );
		return $M;
	}
	
	public static function euler_xzy( object|array $V , object $M ) : object
	{
		$M = static::Mat4( $M );	
		static::$ffi->glmc_euler_xzy( $V , $M );
		return $M;
	}
	
	public static function euler_yzx( object|array $V , object $M ) : object
	{
		$M = static::Mat4( $M );	
		static::$ffi->glmc_euler_yzx( $V , $M );
		return $M;
	}
	
	public static function euler_yxz( object|array $V , object $M ) : object
	{
		$M = static::Mat4( $M );	
		static::$ffi->glmc_euler_yxz( $V , $M );
		return $M;
	}
	
	
	// frustum.h -------------------------------------------------------
	
	public static function FrustumPlanes( /*TODO add init params */ ) : object
	{
		return static::$ffi->new( static::$ffi_typeof_FrustumPlanes );
	}
	public static function FrustumCorners( /*TODO add init params */ ) : object
	{
		return static::$ffi->new( static::$ffi_typeof_FrustumCorners );
	}
	public static function PlaneCorners( /*TODO add init params */ ) : object
	{
		return static::$ffi->new( static::$ffi_typeof_PlaneCorners );
	}
	
	public static function frustum_planes( object $M , object $Planes=null ) : object
	{
		$Planes ??= static::FrustumPlanes();
		
		static::$ffi->glmc_frustum_planes( $M , $Planes->v );
		
		return $Planes;
	}
	
	public static function frustum_corners( object $M , object $Corners=null ) : object
	{
		$Corners ??= static::FrustumCorners();
		
		static::$ffi->glmc_frustum_corners( $M , $Corners->v );
		
		return $Corners;
	}
	
	public static function frustum_center( object $Corners , object $V_dest=null ) : object
	{
		$V_dest = static::Vec4( $V_dest );
		
		static::$ffi->glmc_frustum_center( $Corners->v , $V_dest );
		
		return $V_dest;
	}
	
	public static function frustum_box( object $Corners , object $M , object|array $Aabb ) : object
	{
		$Aabb = GLM::Aabb( $Aabb );
		
		static::$ffi->glmc_frustum_box( $Corners->v , $M , $Aabb->v );
		
		return $Aabb;
	}
	
	public static function frustum_corners_at( object $Corners_f , float $splitDist , float $farDist , object $Corners_p=null ) : object
	{
		if ( $Corners_p === null )
		{
			$Corners_p = GLM::PlaneCorners();
		}
		
		static::$ffi->glmc_frustum_corners_at( $Corners_f->v , $splitDist , $farDist , $Corners_p->v );
		
		return $Corners_p ;
	}
}

class Vec2
{
	public static function __callStatic( string $method , array $args ) : mixed
	{
		$callable = [GLM::$ffi, 'glmc_vec2_'.$method];
		
		$res = $callable(...$args);
		
		//return $res;
		
		if ( ! GLM::ffi_returned_void( $res ) ) return $res;
		
		// Most time, the last parameter is a destination vector,
		// so we can return it instead of void.
		// The only exception is glmc_vec2_clamp() which will be
		// overriden below. 
		
		return $args[ count( $args ) -1 ]; 
	}
	
	public static function new( object|array|int $A = null ) : object
	{
		if ( is_int( $A ) )
		{
			return GLM::$ffi->new("vec2[$A]");
		}
		
		$V = GLM::$ffi->new( GLM::$ffi_typeof_vec2 );
		
		if ( is_countable( $A ) ) // works with both PHP and FFI arrays
		{
			switch( count( $A ) )
			{
				case 0:
				case 1:
					break; // ignore
				
				default:
					$V[ 0 ] = $A[ 0 ] ;
					$V[ 1 ] = $A[ 1 ] ;
				break;
			}
		}
		
		return $V;
	}
	
	public static function clone( object $A ) : object
	{
		$D = GLM::$ffi->new( GLM::$ffi_typeof_vec2 );
		GLM::$ffi->glmc_vec2_copy( $A , $D );
		return $D;
	}
	
	public static function clamp( object $V , float $minval , float $maxval ) : object
	{
		// See __callStatic for the reason why it is overriden.
		
		GLM::$ffi->glmc_vec2_clamp( $V , $minval , $maxval );
		return $V;
	}
	
}

class Vec3
{
	public static function __callStatic( string $method , array $args ) : mixed
	{
		$callable = [GLM::$ffi, 'glmc_vec3_'.$method];
		
		$res = $callable(...$args);
		
		//return $res;
		
		if ( ! GLM::ffi_returned_void( $res ) ) return $res;
		
		// Most time, the last parameter is a destination vector,
		// so we can return it instead of void.
		// The exceptions are :
		// - glmc_vec3_rotate( v , angle , axis )
		// - glmc_vec3_clamp( v , min, max )
		// - glmc_vec3_fill( v , val )
		// They will be overriden below. 
		
		return $args[ count( $args ) -1 ]; 
	}
	
	public static function new( object|array|int $A = null , float $_z = 0.0 ) : object
	{
		if ( is_int( $A ) )
		{
			return GLM::$ffi->new("vec3[$A]");
		}
		
		$V = GLM::$ffi->new( GLM::$ffi_typeof_vec3 );
		
		if ( is_countable( $A ) ) // works with both PHP and FFI arrays
		{
			switch( count( $A ) )
			{
				case 0:
				case 1:
					break; // ignore
				
				case 2:
					$V[ 0 ] = $A[ 0 ] ;
					$V[ 1 ] = $A[ 1 ] ;
					$V[ 2 ] =     $_z ;
				break;
			
				default:
					$V[ 0 ] = $A[ 0 ] ;
					$V[ 1 ] = $A[ 1 ] ;
					$V[ 2 ] = $A[ 2 ] ;
				break;
			}
		}
		
		return $V;
	}
	
	public static function clone( object $A ) : object
	{
		$D = GLM::$ffi->new( GLM::$ffi_typeof_vec3 );
		GLM::$ffi->glmc_vec3_copy( $A , $D );
		return $D;
	}
	
	public static function clamp( object $V , float $minval , float $maxval ) : object
	{
		// See __callStatic for the reason why it is overriden.
		
		GLM::$ffi->glmc_vec3_clamp( $V , $minval , $maxval );
		
		return $V;
	}
	
	public static function rotate( object $V , float $angle , $V_axis ) : object
	{
		// See __callStatic for the reason why it is overriden.
		
		GLM::$ffi->glmc_vec3_rotate( $V , $angle , $V_axis );
		
		return $V;
	}
	
	public static function fill( object $V , float $val ) : object
	{
		// See __callStatic for the reason why it is overriden.
		
		GLM::$ffi->glmc_vec3_fill( $V , $val );
		
		return $V;
	}
	
	
	//CGLM_INLINE
	public static function mix( object $V_from , object $V_to , float $t , object $V_dest ) : object
	{
		GLM::$ffi->glmc_vec3_lerp( $V_from , $V_to , $t , $V_dest );
		
		return $V_dest;
	}
	
	//CGLM_INLINE
	public static function mixc( object $V_from , object $V_to , float $t , object $V_dest ) : object
	{
		GLM::$ffi->glmc_vec3_lerpc( $V_from , $V_to , $t , $V_dest );
		
		return $V_dest;
	}
	
	// ----------------- overrides --------------------------------
	
	public static function add( object $A , object $B , object $D=null ) : object
	{
		$D ??= GLM::$ffi->new( GLM::$ffi_typeof_vec3 );
		GLM::$ffi->glmc_vec3_add( $A , $B , $D );
		return $D;
	}
	
	public static function sub( object $A , object $B , object $D=null ) : object
	{
		$D ??= GLM::$ffi->new( GLM::$ffi_typeof_vec3 );
		GLM::$ffi->glmc_vec3_sub( $A , $B , $D );
		return $D;
	}
	
	public static function adds( object $A , float $s , object $D=null ) : object
	{
		$D ??= GLM::$ffi->new( GLM::$ffi_typeof_vec3 );
		GLM::$ffi->glmc_vec3_adds( $A , $s , $D );
		return $D;
	}
	
	public static function subs( object $A , float $s , object $D=null ) : object
	{
		$D ??= GLM::$ffi->new( GLM::$ffi_typeof_vec3 );
		GLM::$ffi->glmc_vec3_subs( $A , $s , $D );
		return $D;
	}
	
	// TODO : add more overrides when required
	
}


class Vec4
{
	public static function __callStatic( string $method , array $args ) : mixed
	{
		$callable = [GLM::$ffi, 'glmc_vec4_'.$method];
		
		$res = $callable(...$args);
		
		//return $res;
		
		if ( ! GLM::ffi_returned_void( $res ) ) return $res;
		
		// Most time, the last parameter is a destination vector,
		// so we can return it instead of void.
		// The exceptions are :
		// - glmc_vec4_clamp( v , min, max )
		// - glmc_vec4_fill( v , val )
		// They will be overriden below. 
		
		return $args[ count( $args ) -1 ]; 
	}
	
	public static function new( object|array|int $A = null , float $_w = 1.0 ) : object
	{
		if ( is_int( $A ) )
		{
			return GLM::$ffi->new("vec4[$A]");
		}
		
		$V = GLM::$ffi->new( GLM::$ffi_typeof_vec4 );
		
		if ( is_countable( $A ) ) // works with both PHP and FFI arrays
		{
			switch( count( $A ) )
			{
				case 0:
				case 1:
					break; // ignore
				
				case 2:
					$V[ 0 ] = $A[ 0 ] ;
					$V[ 1 ] = $A[ 1 ] ;
					$V[ 2 ] =     0.0 ;
					$V[ 3 ] =     $_w ;
				break;
				
				case 3:
					$V[ 0 ] = $A[ 0 ] ;
					$V[ 1 ] = $A[ 1 ] ;
					$V[ 2 ] = $A[ 2 ] ;
					$V[ 3 ] =     $_w ;
				break;
			
				default:
					$V[ 0 ] = $A[ 0 ] ;
					$V[ 1 ] = $A[ 1 ] ;
					$V[ 2 ] = $A[ 2 ] ;
					$V[ 3 ] = $A[ 3 ] ;
				break;
			}
		}
		
		return $V;
	}
	
	public static function clone( object $A ) : object
	{
		$D = GLM::$ffi->new( GLM::$ffi_typeof_vec4 );
		GLM::$ffi->glmc_vec4_copy( $A , $D );
		return $D;
	}
	
	public static function clamp( object $V , float $minval , float $maxval ) : object
	{
		// See __callStatic for the reason why it is overriden.
		
		GLM::$ffi->glmc_vec4_clamp( $V , $minval , $maxval );
		
		return $V;
	}
	
	public static function fill( object $V , float $val ) : object
	{
		// See __callStatic for the reason why it is overriden.
		
		GLM::$ffi->glmc_vec4_fill( $V , $val );
		
		return $V;
	}
	
	//CGLM_INLINE
	public static function mix( object $V_from , object $V_to , float $t , object $V_dest ) : object
	{
		GLM::$ffi->glmc_vec4_lerp( $V_from , $V_to , $t , $V_dest );
		
		return $V_dest;
	}
	
	//CGLM_INLINE
	public static function mixc( object $V_from , object $V_to , float $t , object $V_dest ) : object
	{
		GLM::$ffi->glmc_vec4_lerpc( $V_from , $V_to , $t , $V_dest );
		
		return $V_dest;
	}
	
	// plane.h ---------------------------------------------------------
	
	public static function plane_normalize( object $V ) : object
	{
		GLM::$ffi->glmc_plane_normalize( $V );
		return $V;
	}
}



class Mat2
{
	public static function __callStatic( string $method , array $args ) : mixed
	{
		$callable = [GLM::$ffi, 'glmc_mat2_'.$method];
		
		$res = $callable(...$args);
		
		//return $res;
		
		if ( ! GLM::ffi_returned_void( $res ) ) return $res;
		
		// Most time, the last parameter is a destination vector,
		// so we can return it instead of void.
		// The exceptions are :
		// - glmc_mat2_scale( m , s )
		// - glmc_mat2_swap_col(mat2 mat, int col1, int col2);
		// - glmc_mat2_swap_row(mat2 mat, int row1, int row2);
		// They will be overriden below. 
		
		return $args[ count( $args ) -1 ]; 
	}
	
	public static function new( object|array|int $A = null ) : object
	{
		if ( is_int( $A ) )
		{
			return GLM::$ffi->new("mat2[$A]");
		}
		
		$M = GLM::$ffi->new( GLM::$ffi_typeof_mat2 );
		
		if ( is_array( $A ) )
		{
			$M[0][0] = $A[0][0] ?? $A[0] ?? 0.0 ;
			$M[0][1] = $A[0][1] ?? $A[1] ?? 0.0 ;
			
			$M[1][0] = $A[1][0] ?? $A[2] ?? 0.0 ;
			$M[1][1] = $A[1][1] ?? $A[3] ?? 0.0 ;
		}
		else
		if ( $A !== null )
		{
			exit("Mat2::new() : Err, unsupported initialisation parameter.".PHP_EOL);
		}
		
		return $M;
	}
	
	public static function clone( object $A ) : object
	{
		$D = GLM::$ffi->new( GLM::$ffi_typeof_mat2 );
		GLM::$ffi->glmc_mat2_copy( $A , $D );
		return $D;
	}
	
	public static function scale( object $M , float $s ) : object
	{
		GLM::$ffi->glmc_mat2_scale( $M , $s );
		
		return $M;
	}
	
	public static function swap_col( object $M , int $col1, int $col2 ) : object
	{
		GLM::$ffi->glmc_mat2_swap_col( $M , $col1 , $col2 );
		
		return $M;
	}
	
	public static function swap_row( object $M, int $row1 , int $row2 ) : object
	{
		GLM::$ffi->glmc_mat2_swap_row( $M , $row1 , $row2 );
		
		return $M;
	}
}


class Mat3
{
	public static function __callStatic( string $method , array $args ) : mixed
	{
		$callable = [GLM::$ffi, 'glmc_mat3_'.$method];
		
		$res = $callable(...$args);
		
		//return $res;
		
		if ( ! GLM::ffi_returned_void( $res ) ) return $res;
		
		// Most time, the last parameter is a destination vector,
		// so we can return it instead of void.
		// The exceptions are :
		// - glmc_mat3_scale( m , s )
		// - glmc_mat3_swap_col(mat2 mat, int col1, int col2);
		// - glmc_mat3_swap_row(mat2 mat, int row1, int row2);
		// They will be overriden below. 
		
		return $args[ count( $args ) -1 ]; 
	}
	
	public static function new( object|array|int $A = null ) : object
	{
		if ( is_int( $A ) )
		{
			return GLM::$ffi->new("mat3[$A]");
		}
		
		$M = GLM::$ffi->new( GLM::$ffi_typeof_mat3 );
		
		if ( is_array( $A ) )
		{
			$M[0][0] = $A[0][0] ?? $A[0] ?? 0.0 ;
			$M[0][1] = $A[0][1] ?? $A[1] ?? 0.0 ;
			$M[0][2] = $A[0][2] ?? $A[2] ?? 0.0 ;
			
			$M[1][0] = $A[1][0] ?? $A[3] ?? 0.0 ;
			$M[1][1] = $A[1][1] ?? $A[4] ?? 0.0 ;
			$M[1][2] = $A[1][2] ?? $A[5] ?? 0.0 ;
			
			$M[2][0] = $A[2][0] ?? $A[6] ?? 0.0 ;
			$M[2][1] = $A[2][1] ?? $A[7] ?? 0.0 ;
			$M[2][2] = $A[2][2] ?? $A[8] ?? 0.0 ;
		}
		else
		if ( $A !== null )
		{
			exit("Mat3::new() : Err, unsupported initialisation parameter.".PHP_EOL);
		}
		
		return $M;
	}
	
	public static function clone( object $A ) : object
	{
		$D = GLM::$ffi->new( GLM::$ffi_typeof_mat3 );
		GLM::$ffi->glmc_mat3_copy( $A , $D );
		return $D;
	}
	
	public static function scale( object $M , float $s ) : object
	{
		GLM::$ffi->glmc_mat3_scale( $M , $s );
		
		return $M;
	}
	
	public static function swap_col( object $M , int $col1, int $col2 ) : object
	{
		GLM::$ffi->glmc_mat3_swap_col( $M , $col1 , $col2 );
		
		return $M;
	}
	
	public static function swap_row( object $M, int $row1 , int $row2 ) : object
	{
		GLM::$ffi->glmc_mat3_swap_row( $M , $row1 , $row2 );
		
		return $M;
	}
}


class Mat4
{
	public static function __callStatic( string $method , array $args ) : mixed
	{
		$callable = [GLM::$ffi, 'glmc_mat4_'.$method];
		
		$res = $callable(...$args);
		
		//return $res;
		
		if ( ! GLM::ffi_returned_void( $res ) ) return $res;
		
		// Most time, the last parameter is a destination vector,
		// so we can return it instead of void.
		// The exceptions are :
		// - glmc_mat4_scale( m , s )
		// - glmc_mat4_scale_p( m , s )
		// - glmc_mat4_swap_col(mat2 mat, int col1, int col2);
		// - glmc_mat4_swap_row(mat2 mat, int row1, int row2);
		// They will be overriden below. 
		
		return $args[ count( $args ) -1 ]; 
	}
	
	public static function new( object|array|int $A = null ) : object
	{
		if ( is_int( $A ) )
		{
			return GLM::$ffi->new("mat4[$A]");
		}
		
		$M = GLM::$ffi->new( GLM::$ffi_typeof_mat4 );
		
		if ( is_array( $A ) )
		{
			$M[0][0] = $A[0][0] ?? $A[0] ?? 0.0 ;
			$M[0][1] = $A[0][1] ?? $A[1] ?? 0.0 ;
			$M[0][2] = $A[0][2] ?? $A[2] ?? 0.0 ;
			$M[0][3] = $A[0][3] ?? $A[3] ?? 0.0 ;
			
			$M[1][0] = $A[1][0] ?? $A[4] ?? 0.0 ;
			$M[1][1] = $A[1][1] ?? $A[5] ?? 0.0 ;
			$M[1][2] = $A[1][2] ?? $A[6] ?? 0.0 ;
			$M[1][3] = $A[1][3] ?? $A[7] ?? 0.0 ;
			
			$M[2][0] = $A[2][0] ?? $A[8] ?? 0.0 ;
			$M[2][1] = $A[2][1] ?? $A[9] ?? 0.0 ;
			$M[2][2] = $A[2][2] ?? $A[10] ?? 0.0 ;
			$M[2][3] = $A[2][3] ?? $A[11] ?? 0.0 ;
			
			$M[3][0] = $A[3][0] ?? $A[12] ?? 0.0 ;
			$M[3][1] = $A[3][1] ?? $A[13] ?? 0.0 ;
			$M[3][2] = $A[3][2] ?? $A[14] ?? 0.0 ;
			$M[3][3] = $A[3][3] ?? $A[15] ?? 0.0 ;
		}
		else
		if ( $A !== null )
		{
			exit("Mat4::new() : Err, unsupported initialisation parameter.".PHP_EOL);
		}

		return $M;
	}
	
	public static function clone( object $A ) : object
	{
		$D = GLM::$ffi->new( GLM::$ffi_typeof_mat4 );
		GLM::$ffi->glmc_mat3_copy( $A , $D );
		return $D;
	}
	
	public static function scale( object $M , float $s ) : object
	{
		GLM::$ffi->glmc_mat4_scale( $M , $s );
		
		return $M;
	}
	
	public static function scale_p( object $M , float $s ) : object
	{
		GLM::$ffi->glmc_mat4_scale_p( $M , $s );
		
		return $M;
	}
	
	public static function swap_col( object $M , int $col1, int $col2 ) : object
	{
		GLM::$ffi->glmc_mat4_swap_col( $M , $col1 , $col2 );
		
		return $M;
	}
	
	public static function swap_row( object $M, int $row1 , int $row2 ) : object
	{
		GLM::$ffi->glmc_mat4_swap_row( $M , $row1 , $row2 );
		
		return $M;
	}

}


class Aabb
{
	public static function __callStatic( string $method , array $args ) : mixed
	{
		$callable = [GLM::$ffi, 'glmc_aabb_'.$method];
		
		$res = $callable(...$args);
		
		return $res;
	}
	
	public static function new( object|array|int $A = null ) : object
	{
		if ( is_int( $A ) )
		{
			return GLM::$ffi->new("Aabb[$A]");
		}
		
		$B = GLM::$ffi->new( GLM::$ffi_typeof_aabb );
		
		if ( is_array( $A ) ) // [ Vmin[] , Vmax[] ]
		{
			GLM::$ffi->glmc_vec3_copy( $B->min , GLM::Vec3( $A[0] ) );
			GLM::$ffi->glmc_vec3_copy( $B->max , GLM::Vec3( $A[1] ) );
		}
		else
		{
			exit("Aabb::new() : Err, unsupported initialisation parameter.".PHP_EOL);
		}
		
		return $B;
	}
	
	public static function clone( object $A ) : object
	{
		$D = GLM::$ffi->new( GLM::$ffi_typeof_aabb );
		GLM::$ffi->glmc_vec3_copy( $A->min , $D->min );
		GLM::$ffi->glmc_vec3_copy( $A->max , $D->max );
		return $D;
	}
	
	public static function transform( object $A , object $M , object $D=null ) : object
	{
		$D = GLM::Aabb( $D );
		
		GLM::$ffi->glmc_aabb_transform( $A->v , $M , $D->v );
		
		return $D;
	}
	
	public static function merge( object $A , object $B , object $D=null ) : object
	{
		$D = GLM::Aabb( $D );
		
		GLM::$ffi->glmc_aabb_merge( $A->v , $B->v , $D->v );
		
		return $D;
	}
	
	public static function crop( object $A , object $B , object $D=null ) : object
	{
		$D = GLM::Aabb( $D );
		
		GLM::$ffi->glmc_aabb_crop( $A->v , $B->v , $D->v );
		
		return $D;
	}
	
	public static function crop_until( object $A , object $B , object $C , object $D=null ) : object
	{
		$D = GLM::Aabb( $D );
		
		GLM::$ffi->glmc_aabb_crop( $A->v , $B->v , $C->v , $D->v );
		
		return $D;
	}
	
	public static function frustum( object $A , object $Planes=null ) : object
	{
		$Planes = GLM::FrustumPlanes( $Planes );
		
		return GLM::$ffi->glmc_aabb_frustum( $A->v , $Planes->v );
	}
	
	public static function invalidate( object $A = null ) : object
	{
		$A = GLM::Aabb( $A );
		
		GLM::$ffi->glmc_aabb_invalidate( $A->v );
		
		return $A;
	}
	
	public static function isvalid( object $A ) : bool
	{	
		return GLM::$ffi->glmc_aabb_isvalid( $A->v );
	}
	
	public static function size( object $A ) : float
	{
		return GLM::$ffi->glmc_aabb_size( $A->v );
	}
	
	public static function radius( object $A ) : float
	{
		return GLM::$ffi->glmc_aabb_radius( $A->v );
	}
	
	public static function center( object $A , object $D=null ) : object
	{
		$D = GLM::Vec3( $D );
		
		GLM::$ffi->glmc_aabb_center( $A->v , $D );
		
		return $D;
	}
	
	public static function aabb( object $A , object $B ) : bool
	{
		return GLM::$ffi->glmc_aabb_aabb( $A->v , $B->v );
	}
	
	public static function point( object $A , object|array $P ) : bool
	{
		$P = GLM::Vec3( $P );
		
		return GLM::$ffi->glmc_aabb_point( $A->v , $P );
	}
	
	public static function contains( object $A , object $B ) : bool
	{		
		return GLM::$ffi->glmc_aabb_contains( $A->v , $B->v );
	}
	
	public static function sphere( object $A , object|array $S , float $_rad=null ) : bool
	{
		$S = GLM::Vec4( $S , $_rad ); 
		
		// Note : 
		// - if count( $S ) == 4, then, $_rad will be ignored.
		// - if count( $S ) < 4 and $_rad is null, Vec4() will set it to default 1.0
		
		return GLM::$ffi->glmc_aabb_sphere( $A->v , $S );
	}
}




class Quat
{
	public static function __callStatic( string $method , array $args ) : mixed
	{
		$callable = [GLM::$ffi, 'glmc_quat_'.$method];
		
		$res = $callable(...$args);
		
		//return $res;
		
		if ( ! GLM::ffi_returned_void( $res ) ) return $res;
		
		// Most time, the last parameter is a destination vector,
		// so we can return it instead of void.
		// The exceptions are : 
		// - glmc_quat_init(versor q, float x, float y, float z, float w);
		// - glmc_quat(versor q, float angle, float x, float y, float z);
		// - glmc_quatv(versor q, float angle, vec3 axis);
		// - glmc_quat_normalize(versor q);
		// - glmc_quat_rotate_at(mat4 model, versor q, vec3 pivot);
		// - glmc_quat_rotate_atm(mat4 m, versor q, vec3 pivot);
		// They will be overriden below. 
		
		return $args[ count( $args ) -1 ]; 
	}
	
	public static function new( object|array|int $A = null , float $_w = 0.0 ) : object
	{
		if ( is_int( $A ) )
		{
			return GLM::$ffi->new("versor[$A]"); //!\ 'versor' is the name used by cglm for quaternion
		}
		
		$V = GLM::$ffi->new( GLM::$ffi_typeof_quat ); //!\ 'versor' is the name used by cglm for quaternion
		
		if ( is_countable( $A ) ) // works with both PHP and FFI arrays
		{
			switch( count( $A ) )
			{
				case 0:
				case 1:
					break; // ignore
				
				case 2:
					$V[ 0 ] = $A[ 0 ] ;
					$V[ 1 ] = $A[ 1 ] ;
					$V[ 2 ] =     0.0 ;
					$V[ 3 ] =     $_w ;
				break;
				
				case 3:
					$V[ 0 ] = $A[ 0 ] ;
					$V[ 1 ] = $A[ 1 ] ;
					$V[ 2 ] = $A[ 2 ] ;
					$V[ 3 ] =     $_w ;
				break;
			
				default:
					$V[ 0 ] = $A[ 0 ] ;
					$V[ 1 ] = $A[ 1 ] ;
					$V[ 2 ] = $A[ 2 ] ;
					$V[ 3 ] = $A[ 3 ] ;
				break;
			}
		}
		
		return $V;
	}
	
	public static function clone( object $A ) : object
	{
		$D = GLM::$ffi->new( GLM::$ffi_typeof_quat ); //!\ 'versor' is the name used by cglm for quaternion
		GLM::$ffi->glmc_quat_copy( $A , $D );
		return $D;
	}
	
	public static function init( object $Q , object|array $V , float $angle = null ) : object
	{
		if ( $angle === null )
		{  
			// glmc_quat_init(versor q, float x, float y, float z, float w);
			GLM::$ffi->glmc_quat_init( $Q , $V[0] , $V[1] , $V[2] , $V[3] );
		}
		else
		{
			// glmc_quat(versor q, float angle, float x, float y, float z);
			// glmc_quatv(versor q, float angle, vec3 axis);
			$V = GLM::Vec3( $V );
			GLM::$ffi->glmc_quatv( $Q , $angle , $V );
		}
		
		return $Q;
	}
	
	
	public static function normalize( object|array $Q ) : object
	{
		$Q = GLM::Quat( $Q );
		GLM::$ffi->glmc_quat_normalize( $Q );
		return $Q;
	}
	
	public static function rotate_at( object $M , object|array $Q , object|array $V_pivot ) : object
	{
		$Q       = GLM::Quat( $Q       );
		$V_pivot = GLM::Vec3( $V_pivot );
		
		GLM::$ffi->glmc_quat_rotate_at( $M , $Q , $V_pivot );
		
		return $M;
	}
	
	public static function rotate_at_m( object $M , object|array $Q , object|array $V_pivot ) : object
	{
		$Q       = GLM::Quat( $Q       );
		$V_pivot = GLM::Vec3( $V_pivot );
		
		GLM::$ffi->glmc_quat_rotate_atm( $M , $Q , $V_pivot );
		
		return $M;
	}
}

