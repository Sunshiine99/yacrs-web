var mcqEditTemplate = '\
    <form action="." method="POST" class="form-horizontal">\
        <input id="questionType" name="questionType" value="mcq" type="hidden">\
        <input name="id" value="" type="hidden">\
        <div class="form-group">\
            <label class="col-sm-2 control-label" for="question">Question</label>\
            <div class="col-sm-10">\
                <input class="form-control" name="question" id="mcQuestion" value="" size="80" type="text" tabindex="1">\
            </div>\
        </div>\
        <div class="form-group">\
            <label for="definition" class="control-label col-sm-2">Choices</label>\
            <div class="col-sm-10">\
                <div id="mcq-choices"></div>\
            </div>\
        </div>\
        <div class="form-group">\
            <div class="col-sm-10 col-sm-offset-2">\
                <input class="submit btn btn-primary" id="createButton" name="submit" value="Create" type="submit" tabindex="1">\
                <a id="mcq-add-option" class="btn btn-default pull-right">Add Another Option</a>\
            </div>\
        </div>\
    </form>';

var textEditTemplate = '\
    <form action="." method="POST" class="form-horizontal">\
        <input id="questionType" name="questionType" value="text" type="hidden">\
        <input name="id" value="" type="hidden">\
        <div class="form-group">\
            <label class="col-sm-2 control-label" for="question">Question</label>\
            <div class="col-sm-10">\
                <input class="form-control" name="question" id="textQuestion" value="" size="80" type="text" tabindex="1">\
            </div>\
        </div>\
        <div class="form-group">\
            <div class="col-sm-10 col-sm-offset-2">\
                <input class="submit btn btn-primary" id="createButton" name="submit" value="Create" type="submit" tabindex="1">\
            </div>\
        </div>\
    </form>';