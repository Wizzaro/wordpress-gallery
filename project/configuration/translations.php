<?php
//This is document when defined all translations for poeedit

// For test add include dirname( __FILE__ ) . '/src/configuration/translations.php'; die(); to main plugin file

$languages_domain = 'wizzaro-gallery-v1';

echo '<p>Metabox</p>';
echo __( 'Images', $languages_domain ); echo '</br>';
echo __( 'You do not have permission to upload files.', $languages_domain ); echo '</br>';
echo sprintf( __('The web browser on your device cannot be used to upload files. You may be able to use the <a href="%s">native app for your device</a> instead.', $languages_domain), 'https://apps.wordpress.org/' ); echo '</br>';
echo __( 'Set as thumbnail',$languages_domain ); echo '</br>';
echo __( 'Preview image' ,$languages_domain ); echo '</br>';
echo __( 'Edit image' ,$languages_domain ); echo '</br>';
echo __( 'Delete image' ,$languages_domain ); echo '</br>';
echo __( 'Error during set image as thumbnail.' ,$languages_domain ); echo '</br>';
echo __( 'Error during deleting image.' ,$languages_domain ); echo '</br>';
echo __( 'In this gallery has no photos. Upload something.' ,$languages_domain ); echo '</br>';

echo '<p>Controller Images</p>';
echo __( 'Failed to write file to disk.' ,$languages_domain ); echo '</br>';
echo __( 'Unknown galley.' ,$languages_domain ); echo '</br>';
echo __( 'You are not allowed to edit this item.' ,$languages_domain ); echo '</br>';
echo __( 'No file was uploaded.' ,$languages_domain ); echo '</br>';

echo __( 'Error during set image as thumbnail.' ,$languages_domain ); echo '</br>';
echo __( 'Unknown galley.' ,$languages_domain ); echo '</br>';
echo __( 'You are not allowed to edit this item. Has your session expired?' ,$languages_domain ); echo '</br>';
echo __( 'Image no exist. Has it been deleted already?' ,$languages_domain ); echo '</br>';

echo __( 'Error during deleting image.' ,$languages_domain ); echo '</br>';
echo __( 'Unknown galley.' ,$languages_domain ); echo '</br>';
echo __( 'You are not allowed to delete this item. Has your session expired?' ,$languages_domain ); echo '</br>';

echo '<p>Controler Post type</p>';
echo __( 'Gallery Categories ' ,$languages_domain ); echo '</br>';
echo __( 'Gallery Category ' ,$languages_domain ); echo '</br>';
echo __( 'All Categories' ,$languages_domain ); echo '</br>'; 
echo __( 'Edit Category' ,$languages_domain ); echo '</br>';
echo __( 'View Category' ,$languages_domain ); echo '</br>';
echo __( 'Update Category' ,$languages_domain ); echo '</br>';
echo __( 'Add New Category' ,$languages_domain ); echo '</br>';
echo __( 'New Category Name' ,$languages_domain ); echo '</br>';
echo __( 'Parent Category' ,$languages_domain ); echo '</br>';
echo __( 'Parent Category:' ,$languages_domain ); echo '</br>';
echo __( 'Search Categories' ,$languages_domain ); echo '</br>';
echo __( 'No categories found.' ,$languages_domain ); echo '</br>';

echo __( 'Galeries',$languages_domain ); echo '</br>';
echo __( 'Gallery',$languages_domain ); echo '</br>';
echo __( 'Add Gallery',$languages_domain ); echo '</br>';
echo __( 'Add New Gallery',$languages_domain ); echo '</br>';
echo __( 'Edit Gallery',$languages_domain ); echo '</br>';
echo __( 'Edit Gallery',$languages_domain ); echo '</br>';
echo __( 'New Gallery',$languages_domain ); echo '</br>';
echo __( 'View Gallery',$languages_domain ); echo '</br>';
echo __( 'Search Galleries',$languages_domain ); echo '</br>';
echo __( 'No Galeries found',$languages_domain ); echo '</br>';
echo __( 'No Galeries found in trash',$languages_domain ); echo '</br>';
echo __( 'All Galleries',$languages_domain ); echo '</br>';
echo __( 'Galleries Archives',$languages_domain ); echo '</br>';
echo __( 'Insert into gallery',$languages_domain ); echo '</br>';
echo __( 'Uploaded to this gallery',$languages_domain ); echo '</br>';
echo __( 'Galeries',$languages_domain ); echo '</br>';

echo '<p>Service Images</p>';
echo sprintf( __( 'Unable to create directory %s. Is its parent directory writable by the server?',$languages_domain ), 'tets' ); echo '</br>';
echo __( 'You do not have permission to upload files.' ,$languages_domain ); echo '</br>';
echo __( 'The uploaded file exceeds the upload_max_filesize directive in php.ini.' ,$languages_domain ); echo '</br>';
echo __( 'The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form.' ,$languages_domain ); echo '</br>';
echo __( 'The uploaded file was only partially uploaded.' ,$languages_domain ); echo '</br>';
echo __( 'No file was uploaded.' ,$languages_domain ); echo '</br>';
echo __( 'Missing a temporary folder.' ,$languages_domain ); echo '</br>';
echo __( 'Failed to write file to disk.' ,$languages_domain ); echo '</br>';
echo __( 'File upload stopped by extension.' ,$languages_domain ); echo '</br>';
echo __( 'File is empty. Please upload something more substantial.' ,$languages_domain ); echo '</br>';
echo __( 'Specified file failed upload.' ,$languages_domain ); echo '</br>';
echo __( 'Sorry, this file type is not permitted for security reasons.' ,$languages_domain ); echo '</br>';
echo __( 'This image already exists in the gallery.' ,$languages_domain ); echo '</br>';
echo sprintf( __('The uploaded file could not be moved to %s.' ,$languages_domain), 'test' ); echo '</br>';
echo __( 'Failed to write file to disk.' ,$languages_domain ); echo '</br>';
echo __( 'Failed to write file to disk.' ,$languages_domain ); echo '</br>';
echo __( 'Image no exist. Has it been deleted already?' ,$languages_domain ); echo '</br>';
echo __( 'Error during deleting image. Has it been deleted already?' ,$languages_domain ); echo '</br>';

echo '<p>Settings Tab Image</p>';

echo __( 'Image' ,$languages_domain ); echo '</br>';
echo __( 'Image size',$languages_domain ); echo '</br>';
echo __( 'Width',$languages_domain ); echo '</br>';
echo __( 'Height',$languages_domain ); echo '</br>';
echo __( 'Image thumbnail size',$languages_domain ); echo '</br>';
echo __( 'Default alt text',$languages_domain ); echo '</br>';
echo __( 'Text',$languages_domain ); echo '</br>';
echo __( 'In this text you can use shortcode "{gallery_name}", which include gallery title and "{image_name}", which include image name"',$languages_domain ); echo '</br>';

echo '<p>View component metabox images uploader</p>';

printf( __( 'You are using the multi-file uploader. Problems? Try the <a href="%1$s" target="%2$s">browser uploader</a> instead.',$languages_domain ), '#', '_self' ); echo '</br>';
printf( __( 'Maximum upload file size: %s.',$languages_domain ), '23' ); echo '</br>';

_e( 'Add new photos' ,$languages_domain ); echo '</br>';
_e('Drop files here',$languages_domain ); echo '</br>';
_ex('or', 'Uploader: Drop files here - or - Select Files',$languages_domain ); echo '</br>';
esc_attr_e('Select Files',$languages_domain ); echo '</br>';
_e('Upload',$languages_domain ); echo '</br>';
_e( 'You are using the browser&#8217;s built-in file uploader. The WordPress uploader includes multiple file selection and drag and drop capability. <a href="#">Switch to the multi-file uploader</a>.' ,$languages_domain ); echo '</br>';

echo '<p>View component metabox images images</p>';

_e( 'In this gallery has no photos. Upload something.' ,$languages_domain ); echo '</br>';
_e( 'Markings on the picture:' ,$languages_domain ); echo '</br>';
_e( 'Gallery thumbnail.' ,$languages_domain ); echo '</br>';
_e( 'Image is not visible on gallery page. When you save gallery then will be visible.' );echo '</br>';
_e( 'Buttons:' ,$languages_domain ); echo '</br>';

echo '<p>View controller images post gallery</p>';
_e( 'Loading images' ,$languages_domain ); echo '</br>';