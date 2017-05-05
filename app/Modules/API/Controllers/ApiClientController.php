<?php

/**
 * This file is part of FusionInvoice.
 *
 * (c) FusionInvoice, LLC <jessedterry@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FI\Modules\Api\Controllers;

use FI\Modules\Clients\Repositories\ClientRepository;
use FI\Modules\Clients\Validators\ClientValidator;

class ApiClientController extends ApiController
{
    public function __construct(ClientRepository $clientRepository, ClientValidator $clientValidator)
    {
        parent::__construct();
        $this->clientRepository = $clientRepository;
        $this->clientValidator  = $clientValidator;
    }

    public function lists()
    {
        return response()->json($this->clientRepository->paginate());
    }

    public function show()
    {
        if ($client = $this->clientRepository->find(request('id')))
        {
            return response()->json($client);
        }

        return response()->json([trans('fi.record_not_found')], 400);

    }

    public function create()
    {
        $input = request()->except('key', 'signature', 'timestamp', 'endpoint');

        $validator = $this->clientValidator->getValidator($input);

        if ($validator->fails())
        {
            return response()->json($validator->errors()->all(), 400);
        }

        return response()->json($this->clientRepository->create($input));
    }

    public function update()
    {
        if ($this->clientRepository->find(request('id')))
        {
            $input = request()->except('key', 'signature', 'timestamp', 'endpoint');

            $validator = $this->clientValidator->getApiUpdateValidator($input, $input['id']);

            if ($validator->fails())
            {
                return response()->json($validator->errors()->all(), 400);
            }

            return response()->json($this->clientRepository->update($input, $input['id']));
        }

        return response()->json([trans('fi.record_not_found')], 400);

    }

    public function delete()
    {
        $validator = $this->validator->make(request()->only('id'), ['id' => 'required']);

        if ($validator->fails())
        {
            return response()->json($validator->errors()->all(), 400);
        }

        if ($this->clientRepository->find(request('id')))
        {
            $this->clientRepository->delete(request('id'));

            return response(200);
        }

        return response()->json([trans('fi.record_not_found')], 400);
    }
}