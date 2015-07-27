

function mapExaminationToPcr()
{
    var examinationMap = {
            "#Element_OphTrOperationnote_Surgeon_surgeon_id": {
                "pcr": '.pcr_doctor_grade',
                "func": setSurgeonFromNote,
                "init": true
            },
            "#OEModule_OphCiExamination_models_Element_OphCiExamination_AnteriorSegment_right_nuclear_id,#OEModule_OphCiExamination_models_Element_OphCiExamination_AnteriorSegment_left_nuclear_id": {
                "pcr": 'select[name="brunescent_white_cataract"]',
                "func": setPcrBrunescent
            },
            "#ed_canvas_edit_left_39_pxe_control,#ed_canvas_edit_right_39_pxe_control": {
                "pcr":  'select[name="pxf_phako"]',
                "func": setPcrPxf
            },
            "#ed_canvas_edit_left_39_pupilSize_control,#ed_canvas_edit_right_39_pupilSize_control": {
                "pcr":  'select[name="pupil_size"]',
                "func": setPcrPupil
            },
        },
        examinationObj,
        examinationEl;

    for(examinationEl in examinationMap){
        if(examinationMap.hasOwnProperty(examinationEl)){
            examinationObj = examinationMap[examinationEl];
            if(typeof examinationObj.func === 'function'){
                $('#event-content').on('change', examinationEl, examinationObj.pcr, examinationObj.func);
            }
            if(typeof examinationObj.init !== 'undefined' && examinationObj.init){
                $(examinationEl).trigger('change', [examinationObj.pcr]);
            }
        }
    }
}

function getPcrContainer(ev)
{
    var isRight = (ev.target.id.indexOf('right') > -1),
        $container = $('#ophCiExaminationPCRRiskLeftEye');

    if(isRight){
        $container = $('#ophCiExaminationPCRRiskRightEye');
    }

    return $container;
}

function setSurgeonFromNote(ev, pcrEl)
{
    if(!pcrEl){
        pcrEl = ev.data;
    }

    var surgeonId = $(ev.target).val();
    if(!surgeonId){
        $('.pcr_doctor_grade').val('');
        pcrCalculate('left');
        pcrCalculate('right');
        return;
    }

    $.ajax({
        'type': 'GET',
        'url': '/user/surgeonGrade/',
        'data': {'id': surgeonId},
        'success': function(data){
            $(pcrEl).val(data.id);
            pcrCalculate('left');
            pcrCalculate('right');
        }
    });
}

function setPcrBrunescent(ev, pcrEl)
{
    if(!pcrEl){
        pcrEl = ev.data;
    }

    var $container = getPcrContainer(ev);
    var $cataractDrop = $(ev.target);
    if($cataractDrop.find(':selected').data('value') === 'Brunescent'){
        $container.find(pcrEl).val('Y');
    } else {
        $container.find(pcrEl).val('N');
    }
    pcrCalculate('left');
    pcrCalculate('right');
}

function setPcrPxf(ev, pcrEl)
{
    if(!pcrEl){
        pcrEl = ev.data;
    }

    var $container = getPcrContainer(ev);

    if(ev.target.checked){
        $container.find(pcrEl).val('Y');
    } else {
        $container.find(pcrEl).val('N');
    }
    pcrCalculate('left');
    pcrCalculate('right');
}

function setPcrPupil(ev, pcrEl)
{
    if(!pcrEl){
        pcrEl = ev.data;
    }

    var $container = getPcrContainer(ev);
    $container.find(pcrEl).val($(ev.target).val());
    pcrCalculate('left');
    pcrCalculate('right');
}

function capitalizeFirstLetter( input) {
    return input.charAt(0).toUpperCase() + input.slice(1);
}


function collectValues( side ){
    var pcrdata = {},
        $eyeSide = $('#ophCiExaminationPCRRisk' + side + 'Eye');

    pcrdata.age = $eyeSide.find(":input[name='age']").val();
    pcrdata.gender = $eyeSide.find(":input[name='gender']").val();
    pcrdata.glaucoma = $eyeSide.find("select[name='glaucoma']").val();
    pcrdata.diabetic = $eyeSide.find("select[name='diabetic']").val();
    pcrdata.fundalview = $eyeSide.find("select[name='no_fundal_view']").val();
    pcrdata.brunescentwhitecataract = $eyeSide.find("select[name='brunescent_white_cataract']").val();
    pcrdata.pxf = $eyeSide.find("select[name='pxf_phako']").val();
    pcrdata.pupilsize = $eyeSide.find("select[name='pupil_size']").val();
    pcrdata.axiallength = $eyeSide.find("select[name='axial_length']").val();
    pcrdata.alpareceptorblocker = $eyeSide.find("select[name='arb']").val();
    pcrdata.abletolieflat = $eyeSide.find("select[name='abletolieflat']").val();
    pcrdata.doctorgrade = $eyeSide.find("select[name='doctor_grade_id']").val();

    if(pcrdata.glaucoma == 'Y') {
        $("#nkglaucoma"+side.toLowerCase()).hide();
    }

    if(pcrdata.pxf == 'Y') {
        $("#nkpxf"+side.toLowerCase()).hide();
    }

    if(pcrdata.alpareceptorblocker  == 'Y') {
        $("#nkarb"+side.toLowerCase()).hide();
    }

    if(pcrdata.axiallength > 0) {
        $("#nkaxial"+side.toLowerCase()).hide();
    }

    if(pcrdata.diabetic == 'Y') {
        $("#nkdiabetic"+side.toLowerCase()).hide();
    }

    if(pcrdata.fundalview == 'Y') {
        $("#nknofv"+side.toLowerCase()).hide();
    }

    return pcrdata;
}

function calculateORValue( inputValues ){
    var OR ={};
    var ORMultiplicated = 1;  // base value

    // multipliers for the attributes and selected values
    OR.age = {'1':1, '2':1.14, '3':1.42, '4':1.58, '5':2.37};
    OR.gender = {'Male':1.28, 'Female':1};
    OR.glaucoma = {'Y':1.30, 'N':1};
    OR.diabetic = {'Y':1.63, 'N':1};
    OR.fundalview = {'Y':2.46, 'N':1};
    OR.brunescentwhitecataract = {'Y':2.99, 'N':1};
    OR.pxf = {'Y':2.92, 'N':1};
    OR.pupilsize = {'Small': 1.45, 'Medium':1.14, 'Large':1};
    OR.axiallength = {'1':1, '2':1.47};
    OR.alpareceptorblocker = {'Y':1.51, 'N':1};
    OR.abletolieflat = {'Y':1, 'N':1.27};
    /*
     1 - Consultant
     2 - Associate specialist
     3 - Trust doctor  // !!!??? Staff grade??
     4 - Fellow
     5 - Specialist Registrar
     6 - Senior House Officer
     7 - House officer  -- ???? no value specified!! using: 1
     */
    OR.doctorgrade = {'1':1, '2':0.87, '3':0.36, '4':1.65, '5':1.60, '6': 3.73, '7':1};

    for (var key in inputValues) {
        if( inputValues[key] == "NK" || inputValues[key] == 0){
            return false;
        }
        ORMultiplicated *= OR[key][inputValues[key]];
    }
    return ORMultiplicated;
}

function pcrCalculate( side ){

   // alert('Called '+side);
    side = capitalizeFirstLetter(side);  // we use this to keep camelCase div names

    var pcrDataValues = collectValues( side );
    var ORValue = calculateORValue( pcrDataValues );
    var pcrRisk;
    var excessRisk;
    var pcrColor;

    if( ORValue ) {
        var pcrRisk = ORValue * (0.00736 / (1 - 0.00736)) / (1 + (ORValue * 0.00736 / (1 - 0.00736))) * 100;
        var averageRiskConst = 1.92;
        excessRisk = pcrRisk / averageRiskConst;
        excessRisk = excessRisk.toFixed(2);
        pcrRisk = pcrRisk.toFixed(2);

        if (pcrRisk <= 1) {
            pcrColor = 'green';
        } else if (pcrRisk > 1 && pcrRisk <= 5) {
            pcrColor = 'orange';
        } else {
            pcrColor = 'red';
        }
    }else{
        pcrRisk = "N/A";
        excessRisk = "N/A";
        pcrColor = 'blue';
    }
    $('#ophCiExaminationPCRRisk'+side+'Eye').find('#pcr-risk-div').css('background', pcrColor);
    $('#ophCiExaminationPCRRisk'+side+'Eye').find('.pcr-span').html(pcrRisk);
    $('#ophCiExaminationPCRRisk'+side+'Eye').find('.pcr-erisk').html(excessRisk);

    $('#ophCiExaminationPCRRisk'+side+'EyeLabel').find('a').css('color', pcrColor);
    $('#ophCiExaminationPCRRisk'+side+'EyeLabel').find('.pcr-span1').html(pcrRisk);

    $('#ophCiExaminationPCRRiskEyeLabel').find('a').css('color', pcrColor);
    $('#ophCiExaminationPCRRiskEyeLabel').find('.pcr-span1').html(pcrRisk);
    //$('#ophCiExaminationPCRRisk'+side+'EyeLabel').find('.pcr-span1').css('color', pcrColor);
}

$(document).ready(function()
{
    mapExaminationToPcr();
    pcrCalculate('left');
    pcrCalculate('right');

    $(document.body).on('change','#ophCiExaminationPCRRiskLeftEye',function(){
        pcrCalculate('left');
    });

    $(document.body).on('change','#ophCiExaminationPCRRiskRightEye',function(){
        pcrCalculate('right');
    });

});