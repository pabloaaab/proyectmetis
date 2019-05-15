# yii2-fpdf
Including FPDF 1.81 in Yii2

Yii2 FPDF
==================
Yii2 Implementation of FPDF

Installation
------------

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
php composer.phar require --prefer-dist inquid/yii2-fpdf "*"
```

or add

```
"inquid/yii2-fpdf": "*"
```

to the require section of your `composer.json` file.


Usage
-----

Once the extension is installed, simply use it in your code by  :

```php
$pdf = new FPDF('P', 'mm', 'Letter');
$pdf->AddPage();
$pdf->SetFont('Arial','B',16);
$pdf->Cell(40,10,'Hello World!');
$pdf->Output();
```


## Documentation
Please read documentation of [FPDF](http://fpdf.de/dokumentation/)
