<?php
namespace Pyaesone17\LaravelPrettyHandler;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Response;
use Illuminate\Database\Eloquent\Model;
use Exception;

class PrettyHandler
{
    protected $request;
    protected $view;

    public function __construct()
    {
        [$this->request, $this->view] = func_get_args();
    }

    /**
     * The method to handle when the user call object as function
     * 
     * @param  Exception $e exception that will recieved from framework hanlder
     * @return \Illuminate\Http\Response    The view of the model that will display
     */
    public function __invoke($e)
    {
        if ($e instanceof ModelNotFoundException) {
            $model = $e->getModel();

            // check the model use Pretty trait and if the user not use, 
            // it will return void and go to normal action.
            
            if ( !$this->isUsedPrettyTrait($model) ) {
                return;
            }

            // Instantiate object to get the trait property
            $model = $this->instantiateObject($model);
            $model->setUpPretty();

            if (count($model->prettyRules)) {
                foreach ($model->prettyRules as $key => $rule) {
                    if (array_key_exists('url', $rule) && array_key_exists('url', $rule)) {
                        if($this->request->is($rule['url'])){
                            if ($this->viewExists($rule['view'])) {
                                return $this->responseView(
                                    $rule['view'],
                                    $e
                                );
                            }

                            else{
                                throw new PrettyViewNotFoundException(
                                    sprintf("The view path %s is not exist in your view folder", 
                                        $rule['view']
                                    )
                                );
                            }                            
                        }
                    }
                }
            }

            if ($model->prettyDefaultView) {

                if (! is_string($model->prettyDefaultView)) {
                    throw new Exception(
                        sprintf("The default pretty view must be string, %s given", 
                            gettype($model->prettyDefaultView)
                        )
                    );
                }   

                else if ($this->viewExists($model->prettyDefaultView)) {
                    return $this->responseView(
                        $model->prettyDefaultView,
                        $e
                    );
                }

                else{
                    throw new PrettyViewNotFoundException(
                        sprintf("The view path %s is not exist in your view folder", 
                            $model->prettyDefaultView
                        )
                    );
                }
            }
            
        }        
    }

    /**
     * Check that the model use Pretty trait to display not found exception
     * 
     * @param  string $model The model name
     * @return boolean        The result of the checking trait
     */
    protected function isUsedPrettyTrait(string $model) : bool
    {
        $usedTraits = class_uses($model);

        return array_key_exists('Pyaesone17\LaravelPrettyHandler\Pretty', $usedTraits);
    }

    /**
     * instantiateObject to get property of pretty
     * 
     * @param  string $model The model name
     * @return Illuminate\Database\Eloquent\Model the model fresh instance       
     */
    protected function instantiateObject(string $model) : Model
    {
        return new $model();
    }

    /**
     * instantiateObject to get property of pretty
     * 
     * @param  string $model The model name
     * @return \Illuminate\Http\Response the response view for pretty     
     */
    protected function responseView($path, $e) : Response
    {
        return response()->view( $path, ['exception' => $e], 404 );
    }

    /**
     * check whether view exists or not against given path
     * 
     * @param  string $path 
     * @return boolean
     */
    protected function viewExists(string $path) : bool
    {
        return $this->view->exists($path);
    }
}

