<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Contact;
use App\Http\Requests\ContactRequest;
use App\Http\Requests\ImportXmlRequest;
use Exception;

class ContactController extends Controller
{
    public function index()
    {
        $contacts = Contact::latest()->paginate(10);
        return view('contacts.index', compact('contacts'));
    }
    

    public function saveData(ContactRequest $request)
    {
        $validated = $request->validated();

        if ($request->id) {
            $contact = Contact::findOrFail($request->id);
            $contact->update($validated);
            $message = "Contact updated successfully.";
        } else {
            Contact::create($validated);
            $message = "Contact added successfully.";
        }

        return redirect()->route('contacts.index')->with('success', $message);
    }

    public function form($id = null)
    {
        try{
            $contact = $id ? Contact::findOrFail(decrypt($id)) : null; // Load contact if editing
            return view('contacts.form', compact('contact'));
        }catch(\Exception $e) {
            abort(500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        Contact::findOrFail($id)->delete();
        return redirect()->route('contacts.index')->with('success', 'Contact deleted successfully.');
    }

    public function importXml(ImportXmlRequest $request)
    {
        $uploadedFile = $request->file('xml_file');

        if (!$uploadedFile) {
            return back()->withErrors(['xml_file' => 'No file uploaded.']);
        }

        $fileContent = file_get_contents($uploadedFile->getPathname());

        if (empty($fileContent)) {
            return back()->withErrors(['xml_file' => 'Uploaded XML file is empty.']);
        }

        if (strpos(trim($fileContent), '<?xml') !== 0) {
            return back()->withErrors(['xml_file' => 'Invalid XML file format.']);
        }

        libxml_use_internal_errors(true);
        $xmlObject = simplexml_load_string($fileContent);

        if ($xmlObject === false) {
            return back()->withErrors(['xml_file' => 'Error parsing XML file.']);
        }

        $successfulImports = 0;
        $skippedDuplicates = 0;
        $duplicateNumbers = [];

        foreach ($xmlObject->contact as $contact) {
            $contactName = (string) $contact->name;
            $contactPhone = (string) $contact->phone;

            if (Contact::where('phone', $contactPhone)->exists()) {
                $duplicateNumbers[] = $contactPhone;
                $skippedDuplicates++;
                continue;
            }

            Contact::create([
                'name' => $contactName,
                'phone' => $contactPhone,
            ]);

            $successfulImports++;
        }

        $message = "{$successfulImports} contacts imported successfully.";

        if ($skippedDuplicates > 0) {
            $message .= " {$skippedDuplicates} duplicate contacts were skipped: " . implode(', ', $duplicateNumbers);
        }
        return redirect()->route('contacts.index')->with('success', $message);



    }

}
