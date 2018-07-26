<!DOCTYPE html>
<html>
<head>
  <title>bpmn-js modeler demo</title>
  <link rel="stylesheet" href="{!! asset('bpmn/css/diagram-js.css') !!}" />
  <link rel="stylesheet" href="{!! asset('bpmn/vendor/bpmn-font/css/bpmn-embedded.css') !!}" />
  <link rel="stylesheet" href="{!! asset('bpmn/css/app.css') !!}" />
</head>
<body>
  <div class="content" id="js-drop-zone">

    <div class="message intro">
      <div class="note">
        Drop BPMN diagram from your desktop or <a id="js-create-diagram" href>create a new diagram</a> to get started.
      </div>
    </div>

    <div class="message error">
      <div class="note">
        <p>Ooops, we could not display the BPMN 2.0 diagram.</p>

        <div class="details">
          <span>cause of the problem</span>
          <pre></pre>
        </div>
      </div>
    </div>

    <div class="canvas" id="js-canvas"></div>
    <div class="properties-panel-parent" id="js-properties-panel"></div>
  </div>

  <ul class="buttons">
    <li>
      download
    </li>
    <li>
      <a id="js-download-diagram" href title="download BPMN diagram">
        BPMN diagram
      </a>
    </li>
    <li>
      <a id="js-download-svg" href title="download as SVG image">
        SVG image
      </a>
    </li>
  </ul>
  <script src="{!! asset('bpmn/index.js') !!}"></script>
</body>
</html>
