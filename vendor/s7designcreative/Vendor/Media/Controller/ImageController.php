<?php
namespace S7D\Vendor\Media\Controller;

use Eventviva\ImageResize;
use S7D\Vendor\Media\Entity\Media;
use S7D\Core\Routing\Controller;

class ImageController extends Controller {

	public function cropForm() {
		return $this->render();
	}

	public function saveImage() {

		$image = $this->request->get('image');
		$name = $this->request->get('filename', md5(uniqid()));

		$image = str_replace('data:image/png;base64,', '', $image);
		$image = base64_decode($image);
		$path = 'upload/' . $name . '.png';
		$i = 0;
		$fileName = $name;
		while(file_exists($path)) {
			$path = 'upload/' . $name . '-' . ++$i . '.png';
			$fileName = $name . '-' . $i;
		}
		mkdir('upload');
		$image = ImageResize::createFromString($image);
		$image->save($path);

		$image = new Media();
		$image->file = $path;
		$image->fileName = $fileName;
		$image->type = 'image/png';
		$this->em->persist($image);
		$this->em->flush();

		$ir = new ImageResize($path);
		$thumbSize = $this->parameters->get('thumbnail.size');
		$ir->crop($thumbSize, $thumbSize);
		$thumbPath = 'upload/' . $fileName . '-' . $thumbSize . 'x' . $thumbSize . '.png';
		$ir->save($thumbPath);

		$thumbnail = new Media();
		$thumbnail->file = $thumbPath;
		$thumbnail->fileName = $fileName;
		$thumbnail->type = 'image/png';
		$thumbnail->parent = $image->id;
		$this->em->persist($thumbnail);

		$this->em->flush();

		return $this->redirectBack();
	}

	public function gallery() {

		$images = $this->getMediaRepo()->findBy(['type' => 'image/png'], [], 100);
		$gallery = [];
		foreach($images as $image) {
			if(!$image->parent) {
				$full = $image->file;
			} else {
				$gallery[] = [
					'full' => $full,
					'thumb' => $image->file,
				];
			}
		}

		return $this->render([
			'gallery' => $gallery,
		]);
	}

	private function getMediaRepo() {
		return $this->em->getRepository('S7D\Vendor\Media\Entity\Media');
	}
}
