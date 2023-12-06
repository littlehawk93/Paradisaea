<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Device $device */

$this->title = 'Paradisaea | Image Upload';
?>

<p>Paradisaea is a simple web application designed to help spread some joy! Simply scan the QR code on a Paradisaea device to upload a photo to that device.<p>
<p>Make sure to only share your QR code with friends! Anyone with the code can upload photos to your device.</p>
<hr />
<p>Want to learn about where the name Paradisaea came from? <a href="https://en.wikipedia.org/wiki/Paradisaea" target="_blank">Click here</a></p>

<hr />
<br />
<h2>Setup &amp; Instructions</h2>
<br />
<br />
<h3>Setup</h3>

<p>Before you can use the device. You must first set up the configuration file for it to connect to your Wi-Fi network.</p>
<p>This allows the device to pull images down from the Paradisaea server and display them.</p>
<p>To configure the device, open it up and retrieve the micro SD card located inside:</p>

<img class="img-responsive" style="max-width: 400px" src="/img/device-2.jpg" />

<p>Connect the micro SD card to a computer and open the <code>CONFIG.INI</code> file in the text editor of your choice.</p>
<p>In the file, add the Wi-Fi name (SSID) and password in the lines of the file shown below. <b>Do not modify any other contents of the file</b>.</n></p>

<img class="img-responsive" style="max-width: 400px" src="/img/config-example.png" />

<br />
<br />
<h3>Device Guide</h3>

<img class="img-responsive" style="max-width: 400px" src="/img/device-1.jpg" />

<p>As shown above, the device has very simple controls: a screen, USB-C port to power the device, a red <i>delete</i> button and white <i>next</i> button.</p>

<p>When you plug the device in, the screen should turn on and the device should begin attempting to connect to your Wi-Fi network. During the connection time, a simple animation will play indicating the device is still connecting:</p>

<img class="img-responsive" style="max-width: 400px" src="/img/device-connecting.gif" />

<p>Once connected, a green success screen will be displayed, followed by the first image loaded for hte device. If there are no images to display, a red "X" will be displayed. If new images are uploaded, simply pressing the <i>next</i> button will remove the red "X" screen.</p>

<img class="img-responsive" style="max-width: 400px" src="/img/device-success.jpg" />

<p>To view another image, press the <i>next</i> button and the screen will go blank and eventually fill with the next image from the image list on the server. Once the end of the list is reached, pushing <i>next</i> will return the first image in the list. The list order is determined by the order the images were uploaded to the server, oldest images first, newest images last.</p>

<p>If you no longer want to see an image, press the <i>delete</i> button and it will be deleted from the server and the image list. The list will start over from the beginning and the first image will be displayed.</p>

<br />
<br />
<h3>Uploading Images</h3>

<p>To upload an image to the device, simply visit the URL on the QR code on the underside of the device. This will take you to a form to upload photos.</p>
<p>While it is not required, it is recommended to resize your images to 160x128 resolution <i>before</i> uploading. This will ensure the best quality.</p>

<p><b>NOTE:</b> only share the image upload URL with people you trust. While it is nearly impossible for someone to randomly guess the URL, anyone who has the URL can upload images to the device at ANY time.</p>

<br />
<br />
<h3>Errors</h3>

<p>If the device encounters an error, either while connecting to the Wi-Fi or any other time, it will display an error screen like below:</p>

<img class="img-responsive" style="max-width: 400px" src="/img/device-error.jpg" />

<p>To exit the error screen, the device must be unplugged and plugged back in.</p>
<p>The error screen displays an error code in the form of dots. Each pattern of dots represents a different error. For more information on what each pattern means, see the table below:</p>

<table class="table table-striped">
    <thead>
        <tr>
            <td style="width: 25%; font-weight: bold;">Pattern</td>
            <td style="width: 75%; font-weight: bold;">Error</td>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td><code style="font-size: 2.0rem">◦ ◦ ◦ •</code></td>
            <td>Failed to connect to the Micro SD card</td>
        </tr>
        <tr>
            <td><code style="font-size: 2.0rem">◦ ◦ • ◦</code></td>
            <td>Invalid Wi-Fi name in config file</td>
        </tr>
        <tr>
            <td><code style="font-size: 2.0rem">◦ ◦ • •</code></td>
            <td>Invalid Wi-Fi password in config file</td>
        </tr>
        <tr>
            <td><code style="font-size: 2.0rem">◦ • ◦ ◦</code></td>
            <td>Invalid config setting</td>
        </tr>
        <tr>
            <td><code style="font-size: 2.0rem">◦ • ◦ •</code></td>
            <td>Invalid config setting</td>
        </tr>
        <tr>
            <td><code style="font-size: 2.0rem">◦ • • ◦</code></td>
            <td>Failed to connect to Wi-Fi network</td>
        </tr>
        <tr>
            <td><code style="font-size: 2.0rem">◦ • • •</code></td>
            <td>Unable to get list of images from Paradisaea server</td>
        </tr>
        <tr>
            <td><code style="font-size: 2.0rem">• ◦ ◦ ◦</code></td>
            <td>Unable to get image data from Paradisaea server</td>
        </tr>
        <tr>
            <td><code style="font-size: 2.0rem">• ◦ ◦ •</code></td>
            <td>Unable to get image data from Paradisaea server</td>
        </tr>
        <tr>
            <td><code style="font-size: 2.0rem">• ◦ • ◦</code></td>
            <td>Failed to delete image on Paradisaea server</td>
        </tr>
    </tbody>
</table>