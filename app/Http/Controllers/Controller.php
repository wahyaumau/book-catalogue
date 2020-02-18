<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use MicrosoftAzure\Storage\Blob\BlobRestProxy;
use MicrosoftAzure\Storage\Common\Exceptions\ServiceException;
use MicrosoftAzure\Storage\Blob\Models\ListBlobsOptions;
use MicrosoftAzure\Storage\Blob\Models\CreateContainerOptions;
use MicrosoftAzure\Storage\Blob\Models\PublicAccessType;

class Controller extends BaseController
{
    /**
     * @OA\Info(
     *      version="1.0.0",
     *      title="Book Catalogue App OpenApi  Documentation",
     *      description="Api Documentation for Book Catalogue App",
     *      @OA\Contact(
     *          email="wahyaumau@gmail.com"
     *      ),
     *      @OA\License(
     *          name="Apache 2.0",
     *          url="http://www.apache.org/licenses/LICENSE-2.0.html"
     *      )
     * )
     *
     * @OA\Server(
     *      url=L5_SWAGGER_CONST_HOST,
     *      description="API Server"
     * )

     *
     * @OA\Tag(
     *     name="Books",
     *     description="API Endpoints of Books"
     * )
     */

    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function apiResponse($status, $message, $result=null){
		if ($result){
			return response()->json([
				'status' => $status,
				'message' => $message,
				'result' => $result
			]);			
		}
		return response()->json([
			'status' => $status,
			'message' => $message,			
		]);
	}

	function createBlobClient() {
        $connectionString = "DefaultEndpointsProtocol=https;AccountName=".env('AZURE_STORAGE_ACCOUNT_NAME').";AccountKey=".env('AZURE_STORAGE_ACCOUNT_KEY');        
        return BlobRestProxy::createBlobService($connectionString);        
    }

    function createContainer($containerName){
        $blobClient = $this->createBlobClient();
        // Create container options object.
        $createContainerOptions = new CreateContainerOptions();

        // Set public access policy.
        $createContainerOptions->setPublicAccess(PublicAccessType::CONTAINER_AND_BLOBS);

        // Set container metadata.
        $createContainerOptions->addMetaData("key1", "value1");        

        // Create container.            
        $blobClient->createContainer($containerName, $createContainerOptions);            
    }

    function generateUniqueFileName($file){
        $fileName = $file->getClientOriginalName();
        $fileNameWOExtension = pathinfo($fileName, PATHINFO_FILENAME);
        return $fileNameWOExtension.'-'.now().'.'.$file->getClientOriginalExtension();
    }
}
