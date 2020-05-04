<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Project;
use App\ProjectStatus;
use App\Http\Resources\Attachment as AttachmentResource;
use App\Http\Resources\AttachmentCollection;
use App\Attachment;

class AttachmentController extends Controller
{
    public function destroy(Attachment $attachment){
        $result = $attachment->delete();
        response()->json([$result],200);
    }

    public function download(Attachment $attachment){

        //dd($attachment);

         try {
            if (is_null($attachment)) throw new \Exception('Arquivo não encontrado');
            return response()->download(storage_path('app/' . $attachment->filename_at_disk), $attachment->orifinal_filename);;
        } catch (\Exception $e) {
            return response()->json(['result' => 'fail', 'message' => $e->getMessage()]);
        }
    }
    public function projectAttachments(Project $project){

        return new AttachmentCollection($project->attachments);
    }
    public function store(){
        try {

            $recebeuArquivo = request()->hasFile('file');
            if (!$recebeuArquivo) throw new \Exception("Nenhum Arquivo foi recebido pelo servidor");


            $project_id = request()->project_id;
            $project = Project::find($project_id);
            if (is_null($project)) throw new \Exception("O projeto informado não exeiste");

            $status_id = request()->status_id;
            $status = ProjectStatus::find($status_id);
            if (is_null($status)) throw new \Exception("A fase informada não existe");

            $month_year = $project->created_at->formatLocalized('%B%Y');
            $filename_at_disk = request()->file->store('attachments/' . $month_year);

            if (empty($filename_at_disk)) throw new \Exception("o arquivo não pode ser enviado para o servidor");

            $original_filename = request()->file->getClientOriginalName();
            $attachment = $project->attachments()->create(
                [
                    "project_id"         => $project->id,
                    "status_id"         => $status->id,
                    "original_filename"  => $original_filename,
                    "filename_at_disk"  => $filename_at_disk,
                    'mime_type' => request()->file->getMimeType(),
                ]
            );

            return new  AttachmentResource($attachment);
        } catch (\Exception $e) {
            return response()->json(['result' => 'fail', 'message' => $e->getMessage()]);
        }
    }
}
