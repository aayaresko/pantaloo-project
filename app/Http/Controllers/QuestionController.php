<?php

namespace App\Http\Controllers;

use App\Question;
use Illuminate\Http\Request;

use App\Http\Requests;

class QuestionController extends Controller
{
    public function index()
    {
        $questions = Question::all();

        return view('admin.faq', ['questions' => $questions]);
    }

    public function view()
    {
        $questions = Question::all();

        return view('agent.faq', ['questions' => $questions]);
    }

    public function create()
    {
        return view('admin.faqCreate');
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'question' => 'required|max:250',
            'answer' => 'required|max:2000'
        ]);

        $question = new Question();
        $question->question = $request->input('question');
        $question->answer = $request->input('answer');
        $question->save();

        return redirect()->route('admin.faq')->with('msg', 'Question was created');
    }

    public function edit(Question $question)
    {
        return view('admin.faqEdit', ['question' => $question]);
    }

    public function update(Request $request, Question $question)
    {
        $this->validate($request, [
            'question' => 'required|max:250',
            'answer' => 'required|max:2000'
        ]);

        $question->question = $request->input('question');
        $question->answer = $request->input('answer');
        $question->save();

        return redirect()->route('admin.faq')->with('msg', 'Question was updated');
    }

    public function delete(Question $question)
    {
        $question->delete();

        return redirect()->route('admin.faq')->with('msg', 'Question was deleted!');
    }
}
