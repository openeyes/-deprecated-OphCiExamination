<?php
/**
 * Created by PhpStorm.
 * User: msmith
 * Date: 29/04/2014
 * Time: 14:46
 */

class OphCiExamination_APITest extends PHPUnit_Framework_TestCase {

	static public function setupBeforeClass()
	{
		Yii::app()->getModule('OphCiExamination');
	}


	public function testgetLetterVisualAcuityForEpisode_Side_hasReading()
	{
		foreach (array('Left', 'Right') as $side) {
			$reading = $this->getMockBuilder('\OEModule\OphCiExamination\models\OphCiExamination_VisualAcuity_Reading')
					->disableOriginalConstructor()
					->setMethods(array('convertTo'))
					->getMock();

			$reading->expects($this->once())
				->method('convertTo')
				->will($this->returnValue('Expected Result'));

			$va = $this->getMockBuilder('\OEModule\OphCiExamination\models\Element_OphCiExamination_VisualAcuity')
					->disableOriginalConstructor()
					->setMethods(array('has' . $side, 'getBestReading'))
					->getMock();

			$va->expects($this->once())
				->method('has' . $side)
				->will($this->returnValue(true));

			$va->expects($this->once())
				->method('getBestReading')
				->will($this->returnValue($reading));

			$api = $this->getMockBuilder('OEModule\OphCiExamination\components\OphCiExamination_API')
					->disableOriginalConstructor()
					->setMethods(array('getElementForLatestEventInEpisode'))
					->getMock();

			$patient = new Patient();
			$episode = new Episode();
			$episode->patient = $patient;

			$api->expects($this->once())
				->method('getElementForLatestEventInEpisode')
				->with($this->equalTo($patient), $this->equalTo($episode), 'models\Element_OphCiExamination_VisualAcuity')
				->will($this->returnValue($va));

			$method = 'getLetterVisualAcuityForEpisode' . $side;
			$this->assertEquals('Expected Result', $api->$method($episode));
		}
	}

	public function testgetLetterVisualAcuityForEpisode_Side_hasNoReading()
	{
		foreach (array('Left', 'Right') as $side) {


			$va = $this->getMockBuilder('\OEModule\OphCiExamination\models\Element_OphCiExamination_VisualAcuity')
					->disableOriginalConstructor()
					->setMethods(array('has' . $side, 'getBestReading', 'getTextForSide'))
					->getMock();

			$va->expects($this->exactly(2))
					->method('has' . $side)
					->will($this->returnValue(true));

			$va->expects($this->exactly(2))
					->method('getBestReading')
					->with(strtolower($side))
					->will($this->returnValue(null));

			$va->expects($this->once())
					->method('getTextForSide')
					->with(strtolower($side))
					->will($this->returnValue('Expected Result'));

			$api = $this->getMockBuilder('\OEModule\OphCiExamination\components\OphCiExamination_API')
					->disableOriginalConstructor()
					->setMethods(array('getElementForLatestEventInEpisode'))
					->getMock();

			$patient = new Patient();
			$episode = new Episode();
			$episode->patient = $patient;

			$api->expects($this->exactly(2))
					->method('getElementForLatestEventInEpisode')
					->with($this->equalTo($patient), $this->equalTo($episode), 'models\Element_OphCiExamination_VisualAcuity')
					->will($this->returnValue($va));
			$method = 'getLetterVisualAcuityForEpisode' . $side;
			$this->assertEquals('Expected Result', $api->$method($episode, true));
			$this->assertNull($api->$method($episode, false));
		}
	}

	public function testgetLetterVisualAcuityForEpisodeBoth_recorded()
	{
		$api = $this->getMockBuilder('\OEModule\OphCiExamination\components\OphCiExamination_API')
				->disableOriginalConstructor()
				->setMethods(array('getLetterVisualAcuityForEpisodeLeft', 'getLetterVisualAcuityForEpisodeRight'))
				->getMock();

		$episode = new Episode();

		$api->expects($this->at(0))
			->method('getLetterVisualAcuityForEpisodeLeft')
			->with($this->equalTo($episode), true)
			->will($this->returnValue('Left VA'));

		$api->expects($this->at(1))
				->method('getLetterVisualAcuityForEpisodeRight')
				->with($this->equalTo($episode), true)
				->will($this->returnValue('Right VA'));

		$this->assertEquals('Right VA on the right and Left VA on the left', $api->getLetterVisualAcuityForEpisodeBoth($episode, true));

		$api->expects($this->at(0))
				->method('getLetterVisualAcuityForEpisodeLeft')
				->with($this->equalTo($episode), false)
				->will($this->returnValue('Left VA'));
		$api->expects($this->at(1))
				->method('getLetterVisualAcuityForEpisodeRight')
				->with($this->equalTo($episode), false)
				->will($this->returnValue(null));

		$this->assertEquals('not recorded on the right and Left VA on the left', $api->getLetterVisualAcuityForEpisodeBoth($episode, false));
	}
}