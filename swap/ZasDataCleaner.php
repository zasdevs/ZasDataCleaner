<?php

/**
 * ZasDataCleaner
 *
 * NOTE: If you've assigned folder limit 120GB, we need
 * 40% empty space always. For example if file size is
 * 200MB x 614 files = 120GB.
 *
 * Now we will clean 40% folder space.
 * new ZasDataCleaner('folder_name','40%','120_GB');
 *
 * @author ZAS Devs ( Syed Alish Naqvi & Shahzaib )
 */
class ZasDataCleaner
{
	/**
	 * Folder Name
	 *
	 * @var string
	 */
	private $folder_name;
	
	/**
	 * Files Delete Limit
	 *
	 * @var integer
	 */
	public $delete_limit;
	
	/**
	 * Folder Memory Limit In GB's
	 *
	 * @var integer
	 */
	private $Folder_memory_limit;
	
	/**
	 * Memory Calculation ( GB's to Bytes )
	 *
	 * @var integer
	 */
	private $assigned;
	
	
	/**
	 * Class Constructor
	 *
	 * @param  string $folder_name         Folder Name (Without Slash)
	 * @param  string $delete_limit        Percentage e.g. 10%
	 * @param  string $Folder_memory_limit Folder Allowed Memory e.g. 120_GB
	 * @return void
	 */
	public function __construct( $folder_name, $delete_limit, $Folder_memory_limit )
	{
		$this->folder_name 		   = $folder_name;
		$this->Folder_memory_limit = $Folder_memory_limit;
		$this->assigned 		   = $this->tobytes();
		$Files_In_Folder 		   = $this->file_count_in_folder();
		$percentage 			   = str_replace( '%', '', $delete_limit );
		$td_Limit 				   = bcdiv( $Files_In_Folder * $percentage / 100, 1, 0 );
		$this->delete_limit 	   = $td_Limit;
	}
	
	/**
	 * Get Count Of Files In Folder
	 *
	 * @return integer
	 */
	public function file_count_in_folder()
	{
		$files = glob( $this->folder_name. '/*' );
		return @count( $files );
	}
	
	/**
	 * Memory Calculation ( GB's to Bytes )
	 *
	 * @return mixed
	 */
	public function tobytes()
	{
		// You can add the rest if needed:
		$types = array( 'B', 'KB', 'MB', 'GB', 'TB', 'PB' );
		
		$type = @explode( '_', $this->Folder_memory_limit )[1];
		$size = @explode( '_', $this->Folder_memory_limit )[0];
		
		if ( $key = array_search( $type, $types ) )
		    return $size * pow( 1024, $key );
	  
		else return 'Invalid Type';
	}
	
	/**
	 * Folder Size
	 *
	 * @credit: https://stackoverflow.com/a/8348396
	 * @return integer
	 */
	public function folder_size()
	{
		$path = $this->folder_name;
		$files = scandir( $path );
		$cleanPath = rtrim( $path, '/' ). '/';
		$total_size = 0;

		foreach( $files as $t )
		{
			if ( $t<>"." && $t<>".." )
			{
				$currentFile = $cleanPath . $t;
				
				if ( is_dir( $currentFile ) )
				{
					$size = foldersize( $currentFile );
					$total_size += $size;
				}
				else {
					$size = filesize( $currentFile );
					$total_size += $size;
				}
			}
		}
		
		return $total_size;
	}
	
	/**
	 * Scan Directory to Get the Files
	 *
	 * @credit: https://stackoverflow.com/a/11923516
	 * @return array
	 */
	private function available_files()
	{
		$dir = $this->folder_name;
		
		// Skip the extra array indexes:
		$skip = ['.', '..'];
		
		$files = [];
		
		foreach ( scandir( $dir ) as $file )
		{
			if ( in_array( $file, $skip ) ) continue;
			
			$files[$file] = filemtime( $dir . '/' . $file );
		}

		asort( $files );
		
		$files = array_keys( $files );

		return ( $files ) ? $files : false;
	}
	
	/**
	 * Get the Scanned Files in the ASC Order
	 *
	 * @return array
	 */
	public function get_available_files()
	{
		return $this->available_files();
	}
	
	/**
	 * Display the Scanned Files in the ASC Order
	 *
	 * @return void
	 */
	public function display_available_files()
	{
		echo '<pre>';
		print_r( $this->get_available_files() );
		echo '</pre>';
	}
	
	/**
	 * Delete Files
	 *
	 * @return boolean
	 */
	public function delete_files()
	{
		if ( $this->folder_size() >= $this->assigned )
		{
			$i = 0;
			
			$folder_name = $this->folder_name;
			
			foreach ( $this->get_available_files() as $file )
			{
				$delete_limit = $this->delete_limit - 1;
				
				if ( $i <= intval( $delete_limit ) )
				{
					if ( file_exists( "$folder_name/" . $file ) )
					{
						unlink( "$folder_name/" . $file );
						clearstatcache();
					}
				}
				
				$i++;
			}
		}
		
		return 'Successfully Deleted';
	}
}
