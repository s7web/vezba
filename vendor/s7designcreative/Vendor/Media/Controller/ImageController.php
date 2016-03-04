<?php
namespace S7D\Vendor\Media\Controller;

use Eventviva\ImageResize;
use S7D\Core\HTTP\ResponseJSON;
use S7D\Vendor\Media\Entity\Media;
use S7D\Core\Routing\Controller;
use S7D\Vendor\Media\Repository\MediaRepository;
use Symfony\Component\Validator\Constraints\Image;

class ImageController extends Controller {

	public function cropForm() {
		return $this->render();
	}

	public function saveImage() {

		$image = $this->request->get('image-base64');
		$image = str_replace('data:image/png;base64,', '', $image);
		$image = base64_decode($image);
		$image = ImageResize::createFromString($image);

		$name = $this->request->get('filename', md5(uniqid()));
		$this->uploadImage($image, $name);

		return $this->redirectBack();
	}

	private function uploadImage(ImageResize $image, $nameOriginal) {

        $slugger = $this->container->slugger;
        $file = $slugger->slugify($nameOriginal);

		$path = 'upload/' . $file . '.jpg';
		$i = 0;
		$fileWithNumber = $file;
		while(file_exists($path)) {
			$path = 'upload/' . $file . '-' . ++$i . '.jpg';
            $fileWithNumber = $file . '-' . $i;
		}

		$image->save($path, IMAGETYPE_JPEG);

		$image = new Media();
		$image->file = $path;
		$image->fileName = $nameOriginal;
		$image->type = 'image/jpeg';
		$this->em->persist($image);
		$this->em->flush();

		$ir = new ImageResize($path);
		$thumbnailSizes = $this->parameters->get('thumbnails');
		foreach($thumbnailSizes as $size) {
			$width = $size[0];
			$height = $size[1];;
			$ir->crop($width, $height);
			$thumbPath = 'upload/' . $fileWithNumber . '-' . $width . 'x' . $height . '.jpg';
			$ir->save($thumbPath, IMAGETYPE_JPEG);

			$thumbnail = new Media();
			$thumbnail->file = $thumbPath;
			$thumbnail->fileName = $nameOriginal;
			$thumbnail->type = 'image/jpeg';
			$thumbnail->parent = $image->id;
			$this->em->persist($thumbnail);
			$this->em->flush();
		}

		return $path;
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

	public function reductorJson($query) {
		$images = $this->getMediaRepo()->search($query, ['image/png', 'image/jpeg'], 50);
		$gallery = [];
		$images = array_reverse($images);
		foreach($images as $image) {
            $type = $image->type === 'image/png' ? 'png' : 'jpg';
			$source = isset($image->meta['source']) ? $image->meta['source'] : '';
			$gallery[] = [
				'image' => '/' . $image->file,
				'thumb' => '/' . str_replace('.' . $type, '-100x100.' . $type, $image->file),
				'source' => $source,
				'saveSourceUrl' => $this->generateUrl('saveSource', $image->id),
			];
		}
		$gallery = array_reverse($gallery);
		return new ResponseJSON($gallery);
	}

	public function reductorSave() {

		$image = new ImageResize($_FILES['file']['tmp_name']);
		$image->crop(960, 720);
		$name = preg_replace('/\..+$/', '', $_FILES['file']['name']);

        $src = $this->uploadImage($image, $name);

		return new ResponseJSON([
			'filelink' => '/' . $src,
		]);
	}

	/**
	 * @return MediaRepository
	 */
	private function getMediaRepo() {
		return $this->em->getRepository('S7D\Vendor\Media\Entity\Media');
	}
}
