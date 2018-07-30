<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8"/>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Hello World</title>

    <!-- required modeler styles -->
    <link rel="stylesheet" href="https://unpkg.com/bpmn-js@2.4.1/dist/assets/diagram-js.css">
    <link rel="stylesheet" href="https://unpkg.com/bpmn-js@2.4.1/dist/assets/bpmn-font/css/bpmn.css">

    <!-- modeler distro -->
    <script src="https://unpkg.com/bpmn-js@2.4.1/dist/bpmn-modeler.development.js"></script>

    <!-- needed for this example only -->
    <script src="https://unpkg.com/jquery@3.3.1/dist/jquery.js"></script>

    <!-- example styles -->
    <style>
        html, body, #canvas {
            height: 100%;
            padding: 0;
            margin: 0;
        }

        .diagram-note {
            background-color: rgba(66, 180, 21, 0.7);
            color: White;
            border-radius: 5px;
            font-family: Arial;
            font-size: 12px;
            padding: 5px;
            min-height: 16px;
            width: 50px;
            text-align: center;
        }

        .needs-discussion:not(.djs-connection) .djs-visual > :nth-child(1) {
            stroke: rgba(66, 180, 21, 0.7) !important; /* color elements as red */
        }

        #save-button {
            position: fixed;
            bottom: 50px;
            left: 40px;
            padding: 20px 50px;
            background-color: darkgray;
        }
        #save-button2 {
            position: fixed;
            bottom: 50px;
            left:  300px;
            padding: 20px 50px;
            background-color: darkgray;
        }
    </style>
</head>
<body>
<div id="canvas"></div>
<div class="form-group">
    <button id="save-button">Salvar Modelo</button>
</div>

<div class="form-group">
    <button id="save-button2">Aplicar Regras</button>
</div>


<script>

    var diagramUrl = 'https://cdn.rawgit.com/bpmn-io/bpmn-js-examples/dfceecba/starter/diagram.bpmn';

    // modeler instance
    var bpmnModeler = new BpmnJS({
        container: '#canvas',
        keyboard: {
            bindTo: window
        }
    });

    /**
     * Save diagram contents and print them to the console.
     */
    function exportDiagram() {

        bpmnModeler.saveXML({format: true}, function (err, xml) {

            if (err) {
                return console.error('could not save BPMN 2.0 diagram', err);
            }
            // var data = {
            //     xml: xml,
            //     _token: $('meta[name="csrf-token"]').attr('content')
            // };
              alert('Este comando vai funcionar em breve!!!');
            $.ajax({
                type: "POST",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "http://projeto.test/admin/xml_store",
                data: {// change data to this object
                    _token : $('meta[name="csrf-token"]').attr('content'),
                },
                dataType: "text",
                success: function(resultData) { alert(data) }
            })
            // alert(xml);
            // console.log('DIAGRAM', data);
        });
    }

    /**
     * Open diagram in our modeler instance.
     *
     * @param {String} bpmnXML diagram to display
     */
    function openDiagram(bpmnXML) {

        // import diagram
        bpmnModeler.importXML(bpmnXML, function (err) {

            if (err) {
                return console.error('could not import BPMN 2.0 diagram', err);
            }

            // access modeler components
            var canvas = bpmnModeler.get('canvas');
            var overlays = bpmnModeler.get('overlays');


            // zoom to fit full viewport
            canvas.zoom('fit-viewport');

            // attach an overlay to a node
            overlays.add('SCAN_OK', 'note', {
                position: {
                    bottom: 0,
                    right: 0
                },
                html: '<div class="diagram-note">Mixed up the labels?</div>'
            });

            // add marker
            canvas.addMarker('SCAN_OK', 'needs-discussion');
        });
    }


    // load external diagram file via AJAX and open it
    $.get(diagramUrl, openDiagram, 'text');

    // wire save button
    $('#save-button').click(exportDiagram);
    $('#save-button2').click(exportDiagram);


</script>
<!--
  Thanks for trying out our BPMN toolkit!

  This example uses the pre-built distribution of the bpmn-js modeler.
  Consider rolling your own distribution to have more flexibility
  regarding which features to include.

  Checkout our advanced examples section to learn more:
  * https://github.com/bpmn-io/bpmn-js-examples#advanced

  To get a bit broader overview over how bpmn-js works,
  follow our walkthrough:
  * https://bpmn.io/toolkit/bpmn-js/walkthrough/

  Related starters:
  * https://raw.githubusercontent.com/bpmn-io/bpmn-js-examples/starter/viewer.html
-->

</body>
</html>
