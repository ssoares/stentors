<?php

function traverseHierarchy($path, $maxWidth, $maxHeight)
{
    $returnArray = array();
    $tmp = array();
    $dir = opendir($path);

    while (($file = readdir($dir)) !== false)
    {
        if ($file[0] == '.')
            continue;

        $fullPath = $path . '/' . $file;
        //Trouver la plus grande image.
        if (!is_dir($fullPath))
        {
            $tmp = explode('_', $file);
            $dim = explode('x', $tmp[0]);
            //construire le nom le chemin de l'image
            if ($dim[0] > $maxWidth && $dim[1] > $maxHeight)
            {
                $srcThumb = $path . '/' . $maxWidth.'x'.$maxHeight.'_'.$tmp[1];
                //remplir le tableau image
                $image = array(
                    'src'       => $srcThumb,
                    'maxWidth'  => $maxWidth,
                    'maxHeight' => $maxHeight
                );
                copy($fullPath, $srcThumb);
                resampled($image);
            }
        }
        else // your if goes here: if(substr($file, -3) == "jpg") or something like that
            traverseHierarchy($fullPath, $maxWidth, $maxHeight);
    }
    
}

function resampled($image)
{
    $image['ext'] = strtolower(substr($image['src'], strrpos($image['src'], '.') + 1));
    list($image['width'], $image['height'], $image['type'], $image['attr']) = getimagesize($image['src']);
    $image['width'] >= $image['height'] ? $image['format'] = 'landscape' : $image['format'] = 'portrait';

    $newDimensions = newDimensions($image);

    if ($image['ext'] == 'jpeg' || $image['ext'] == 'jpg')
    {
        // returns an image identifier representing the image obtained from the given filename.
        $newImage = imagecreatefromjpeg($image['src']);

        // returns an image identifier representing a black image of the specified size.
        $thumb = imagecreatetruecolor($newDimensions['width'] - 1, $newDimensions['height'] - 1);

        // copies a rectangular portion of one image to another image, smoothly interpolating pixel values so that, in particular, reducing the size of an image still retains a great deal of clarity.
        imagecopyresampled($thumb, $newImage, 0, 0, 0, 0, $newDimensions['width'], $newDimensions['height'], $image['width'], $image['height']);

        // reates a JPEG file from the given image
        imagejpeg($thumb, $image['src'], 80);

        // frees any memory associated with newImage
        imagedestroy($newImage);
    }
    elseif ($image['ext'] == 'gif')
    {
        // returns an image identifier representing the image obtained from the given filename.
        $newImage = imagecreatefromgif($image['src']);

        // returns an image identifier representing a black image of the specified size.
        $thumb = imagecreate($newDimensions['width'] - 1, $newDimensions['height'] - 1);

        // returns a color identifier representing the color composed of the given RGB components.
        $trans_color = imagecolorallocate($thumb, 255, 0, 0);

        // sets the transparent color in the given image .
        imagecolortransparent($thumb, $trans_color);

        // copies a rectangular portion of one image to another image, smoothly interpolating pixel values so that, in particular, reducing the size of an image still retains a great deal of clarity.
        imagecopyresampled($thumb, $newImage, 0, 0, 0, 0, $newDimensions['width'], $newDimensions['height'], $image['width'], $image['height']);

        // creates the GIF  file in filename from the image image
        imagegif($thumb, $image['src']);

        // détruit une image
        imagedestroy($newImage);
    }
    elseif ($image['ext'] == 'png')
    {
        $image_source = imagecreatefrompng($image['src']);
        $new_image = imagecreatetruecolor($newDimensions['width'] - 1, $newDimensions['height'] - 1);
        if (function_exists('imagecolorallocatealpha'))
        {
            imagealphablending($new_image, false);
            imagesavealpha($new_image, true);
            $transparent = imagecolorallocatealpha($new_image, 255, 255, 255, 127);
            imagefilledrectangle($new_image, 0, 0, $newDimensions['width'] - 1, $newDimensions['height'] - 1, $transparent);
        }
        imagecolortransparent($new_image, $transparent);
        imagecopyresampled($new_image, $image_source, 0, 0, 0, 0, $newDimensions['width'], $newDimensions['height'], $image['width'], $image['height']);
        imagepng($new_image, $image['src']);
        imagedestroy($new_image);
    }
}

function newDimensions($image)
{
    $newDimensions['width'] = $image['width'];
    $newDimensions['height'] = $image['height'];

    if ($image['format'] == 'landscape')
    {
        if ($image['width'] > $image['maxWidth'])
        {
            $reduce = $image['maxWidth'] / $image['width'];
            $newDimensions['width'] = $image['maxWidth'];
            $newDimensions['height'] = ceil($image['height'] * $reduce);

            if ($newDimensions['height'] > $image['maxHeight'])
            {
                $reduce = $image['maxHeight'] / $image['height'];
                $newDimensions['width'] = ceil($image['width'] * $reduce);
                $newDimensions['height'] = $image['maxHeight'];
            }
        }
    }
    else
    {
        if ($image['height'] > $image['maxHeight'])
        {
            $reduce = $image['maxHeight'] / $image['height'];
            $newDimensions['width'] = ceil($image['width'] * $reduce);
            $newDimensions['height'] = $image['maxHeight'];

            if ($newDimensions['width'] > $image['maxWidth'])
            {
                $reduce = $image['maxWidth'] / $image['width'];
                $newDimensions['width'] = $image['maxWidth'];
                $newDimensions['height'] = ceil($image['height'] * $reduce);
            }
        }
    }
    
    return $newDimensions;
}

if (isset($_REQUEST['submitResize']))
{
    $path = dirname(__FILE__) . '/' . $_REQUEST['moduleName'];

    $maxWidth  = (int) $_REQUEST['maxWidth'];
    $maxHeight = (int) $_REQUEST['maxHeight'];

    traverseHierarchy($path, $maxWidth, $maxHeight);
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1"/>
        <title>Redimensionner images</title>
    </head>
    <body>
        <p>
            <form action="" method="post" enctype="application/x-www-form-urlencoded">
                <dl>
                    <dd>
                        Nom du module à traiter:
                        <input type="text" value="" id="moduleName" name="moduleName"/>
                    </dd>
                    <dd>
                        Nouvelle largeur:
                        <input type="text" value="" id="maxWidth" name="maxWidth"/>
                    </dd>
                    <dd>
                        Nouvelle hauteur:
                        <input type="text" value="" id="maxHeight" name="maxHeight"/>
                    </dd>


                <input type="submit" name="submitResize" id="submitResize" />
                </dl>
            </form>

        </p>
    </body>
</html>