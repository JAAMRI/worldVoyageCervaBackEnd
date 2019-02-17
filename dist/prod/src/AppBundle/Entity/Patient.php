<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;
use JMS\Serializer\Annotation\SerializedName;

/**
 * Patient
 *
 * @ORM\Table(name="zfzmyrjlmj_patients")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\PatientRepository")
 * @ORM\HasLifecycleCallbacks
 * @ExclusionPolicy("all")
 */

class Patient
{
    /**
     * @var int
     *
     * @Expose
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @Expose
     *
     * @SerializedName("patientUniqueid")
     *
     * @ORM\Column(name="patient_uniqueid", type="string", length=32, nullable=false)
     */
    private $patientUniqueid;

    /**
     * @var string
     *
     * @Expose
     *
     * @ORM\Column(name="homephone", type="string", length=55, nullable=true)
     */
    private $homephone;

    /**
     * @var string
     *
     * @Expose
     *
     * @ORM\Column(name="cellphone", type="string", length=55, nullable=true)
     */
    private $cellphone;

    /**
     * @var string
     *
     * @Expose
     *
     * @ORM\Column(name="officephone", type="string", length=55, nullable=true)
     */
    private $officephone;

    /**
     * @var string
     *
     * @Expose
     *
     * @ORM\Column(name="firstname", type="string", length=45, nullable=true)
     */
    private $firstname;

    /**
     * @var string
     *
     * @Expose
     *
     * @ORM\Column(name="lastname", type="string", length=45, nullable=false)
     */
    private $lastname;

    /**
     * @var string
     *
     * @Expose
     *
     * @ORM\Column(name="middlename", type="string", length=45, nullable=true)
     */
    private $middlename;


    /**
     * @var string
     *
     * @Expose
     *
     * @ORM\Column(name="photo", type="string", length=255, nullable=true)
     */
    private $photo;

    /**
     * @var string
     *
     * @Expose
     *
     * @ORM\Column(name="city", type="string", length=100, nullable=true)
     */
    private $city;

    /**
     * @var string
     *
     * @Expose
     *
     * @ORM\Column(name="country", type="string", length=100, nullable=true)
     */
    private $country;

    /**
     * @var string
     *
     * @Expose
     *
     * @ORM\Column(name="state", type="string", length=100, nullable=true)
     */
    private $state;

    /**
     * @var string
     *
     * @Expose
     *
     * @ORM\Column(name="postalcode", type="string", length=15, nullable=true)
     */
    private $postalcode;


    /**
     * @var string
     *
     * @Expose
     *
     * @ORM\Column(name="address", type="text", nullable=true)
     */
    private $address;

    /**
     * @var string
     *
     * @Expose
     *
     * @ORM\Column(name="email", type="string", length=255, nullable=true)
     */
    private $email;

    /**
     * @var string
     *
     * @Expose
     *
     * @ORM\Column(name="gender", type="string", length=255, nullable=true)
     */
    private $gender;

    /**
     * @var \Date
     *
     * @Expose
     *
     * @ORM\Column(name="birthdate", type="date", nullable=true)
     */
    private $birthdate;

    /**
     * @var string
     *
     * @Expose
     *
     * @ORM\Column(name="allergies", type="text", nullable=true)
     */
    private $allergies;

    /**
     * @var string
     *
     * @Expose
     *
     * @SerializedName("drugIntolerance")
     *
     * @ORM\Column(name="drug_intolerance", type="text", nullable=true)
     */
    private $drugIntolerance;


    /**
     * @var string
     *
     * @Expose
     *
     * @SerializedName("smokingStatus")
     *
     * @ORM\Column(name="smoking_status", type="string", length=255, nullable=true)
     */
    private $smokingStatus;

    /**
     * @var boolean
     *
     * @Expose
     *
     * @SerializedName("isPregnant")
     *
     * @ORM\Column(name="is_pregnant", type="boolean", nullable=true)
     */
    private $isPregnant;

    /**
     * @var boolean
     *
     * @Expose
     *
     * @SerializedName("isBreastFeeding")
     *
     * @ORM\Column(name="is_breast_feeding", type="boolean", nullable=true)
     */
    private $isBreastFeeding;

    /**
     * @var string
     *
     * @Expose
     *
     * @ORM\Column(name="emergencycontactname1", type="string", length=90, nullable=true)
     */
    private $emergencycontactname1;


    /**
     * @var string
     *
     * @Expose
     *
     * @ORM\Column(name="emergencycontactphone1", type="string", length=55, nullable=true)
     */
    private $emergencycontactphone1;


    /**
     * @var string
     *
     * @Expose
     *
     * @ORM\Column(name="referredby", type="string", length=85, nullable=true)
     */
    private $referredby;

    /**
     * @var string
     *
     * @Expose
     *
     * @ORM\Column(name="diagnosis", type="text", nullable=true)
     */
    private $diagnosis;

    /**
     * @var string
     *
     * @Expose
     *
     * @ORM\Column(name="notes", type="text", nullable=true)
     */
    private $notes;

    /**
     * @var \DateTime
     *
     * @Expose
     *
     * @ORM\Column(name="createdon", type="datetime", nullable=true)
     */
    private $createdon;

    /**
     * @var string
     *
     * @Expose
     *
     * @ORM\Column(name="username", type="string", length=40, unique=true, nullable=true)
     */
    private $username;

    /**
     * @var string
     *
     * @Expose
     *
     * @ORM\Column(name="password", type="string", length=255, nullable=true)
     */
    private $password;

    /**
     * @var string
     *
     * @Expose
     *
     * @ORM\Column(name="title", type="string", length=12, nullable=true)
     */
    private $title;

    /**
     * @var string
     *
     * @Expose
     *
     * @ORM\Column(name="identificationtype", type="string", length=50, nullable=true)
     */
    private $identificationtype;

    /**
     * @var string
     *
     * @Expose
     *
     * @ORM\Column(name="identificationnumber", type="string", length=60, nullable=true)
     */
    private $identificationnumber;

    /**
     * @var integer
     *
     * @Expose
     *
     * @ORM\Column(name="status", type="integer", nullable=true, options={"default":"1"})
     */
    private $status;

    /**
     * @var string
     *
     * @Expose
     *
     * @SerializedName("reminderLanguage")
     *
     * @ORM\Column(name="reminder_language", type="string", length=55, nullable=true)
     */
    private $reminderLanguage;

    /**
     * @var boolean
     *
     * @Expose
     *
     * @SerializedName("medicalHistoryConsent")
     *
     * @ORM\Column(name="medical_history_consent", type="boolean", nullable=true, options={"default":"0"})
     */
    private $medicalHistoryConsent;

    /**
     * @var string
     *
     * @Expose
     *
     * @SerializedName("preferedLanguage")
     *
     * @ORM\Column(name="prefered_language", type="string", length=55, nullable=true)
     */
    private $preferedLanguage;

    /**
     * @var string
     *
     * @Expose
     * @SerializedName("maritalStatus")
     *
     * @ORM\Column(name="marital_status", type="string", length=35, nullable=true)
     */
    private $maritalStatus;

    /**
     * @var string
     *
     * @Expose
     *
     * @SerializedName("insuredPersonName")
     *
     * @ORM\Column(name="insured_person_name", type="string", length=90, nullable=true)
     */
    private $insuredPersonName;

    /**
     * @var string
     *
     * @Expose
     *
     * @SerializedName("insuredPersonPhone")
     *
     * @ORM\Column(name="insured_person_phone", type="string", length=55, nullable=true)
     */
    private $insuredPersonPhone;

    /**
     * @var string
     *
     * @Expose
     *
     * @SerializedName("insuredPersonIdentificationType")
     *
     * @ORM\Column(name="insured_person_identification_type", type="string", length=45, nullable=true)
     */
    private $insuredPersonIdentificationType;

    /**
     * @var string
     *
     * @Expose
     *
     * @SerializedName("insuredPersonIdentificationNumber")
     *
     * @ORM\Column(name="insured_person_identification_number", type="string", length=55, nullable=true)
     */
    private $insuredPersonIdentificationNumber;

    /**
     * @var integer
     *
     * @Expose
     *
     * @SerializedName("insuranceCompanyId")
     *
     *
     * @ORM\Column(name="insurance_company_id", type="integer", nullable=true)
     */
    private $insuranceCompanyId;

    /**
     * @var string
     *
     * @Expose
     *
     * @SerializedName("insurancePolicyNumber")
     *
     * @ORM\Column(name="insurance_policy_number", type="string", length=45, nullable=true)
     */
    private $insurancePolicyNumber;

    /**
     * @var integer
     *
     * @Expose
     *
     * @SerializedName("doneBy")
     *
     * @ORM\Column(name="done_by", type="integer", length=12, nullable=true)
     */
    private $doneBy;

    /**
     * @var integer
     *
     * @Expose
     *
     * @ORM\Column(name="rating", type="integer", nullable=true, options={"default":"5"})
     */
    private $rating;

    /**
     * @var string
     *
     * @Expose
     *
     * @ORM\Column(name="profession", type="string", length=120, nullable=true)
     */
    private $profession;


    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getPatientUniqueid()
    {
        return $this->patientUniqueid;
    }

    /**
     * @param string $patientUniqueid
     */
    public function setPatientUniqueid($patientUniqueid)
    {
        $this->patientUniqueid = $patientUniqueid;
    }

    /**
     * @return string
     */
    public function getHomephone()
    {
        return $this->homephone;
    }

    /**
     * @param string $homephone
     */
    public function setHomephone($homephone)
    {
        $this->homephone = $homephone;
    }

    /**
     * @return string
     */
    public function getCellphone()
    {
        return $this->cellphone;
    }

    /**
     * @param string $cellphone
     */
    public function setCellphone($cellphone)
    {
        $this->cellphone = $cellphone;
    }

    /**
     * @return string
     */
    public function getOfficephone()
    {
        return $this->officephone;
    }

    /**
     * @param string $officephone
     */
    public function setOfficephone($officephone)
    {
        $this->officephone = $officephone;
    }

    /**
     * @return string
     */
    public function getFirstname()
    {
        return $this->firstname;
    }

    /**
     * @param string $firstname
     */
    public function setFirstname($firstname)
    {
        $this->firstname = $firstname;
    }

    /**
     * @return string
     */
    public function getLastname()
    {
        return $this->lastname;
    }

    /**
     * @param string $lastname
     */
    public function setLastname($lastname)
    {
        $this->lastname = $lastname;
    }

    /**
     * @return string
     */
    public function getMiddlename()
    {
        return $this->middlename;
    }

    /**
     * @param string $middlename
     */
    public function setMiddlename($middlename)
    {
        $this->middlename = $middlename;
    }

    /**
     * @return string
     */
    public function getPhoto()
    {
        return $this->photo;
    }

    /**
     * @param string $photo
     */
    public function setPhoto($photo)
    {
        $this->photo = $photo;
        return $this;
    }

    /**
     * @return string
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * @param string $city
     */
    public function setCity($city)
    {
        $this->city = $city;
    }

    /**
     * @return string
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * @param string $country
     */
    public function setCountry($country)
    {
        $this->country = $country;
    }

    /**
     * @return string
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * @param string $state
     */
    public function setState($state)
    {
        $this->state = $state;
    }

    /**
     * @return string
     */
    public function getPostalcode()
    {
        return $this->postalcode;
    }

    /**
     * @param string $postalcode
     */
    public function setPostalcode($postalcode)
    {
        $this->postalcode = $postalcode;
    }

    /**
     * @return string
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * @param string $address
     */
    public function setAddress($address)
    {
        $this->address = $address;
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param string $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }


    /**
     * @return string
     */
    public function getGender()
    {
        return $this->gender;
    }

    /**
     * @param string $gender
     */
    public function setGender($gender)
    {
        $this->gender = $gender;
    }

    /**
     * @return \DateTime
     */
    public function getBirthdate()
    {
        return $this->birthdate;
    }

    /**
     * @param \DateTime $birthdate
     */
    public function setBirthdate($birthdate)
    {
        $this->birthdate = $birthdate;
    }

    /**
     * @return string
     */
    public function getAllergies()
    {
        return $this->allergies;
    }

    /**
     * @param string $allergies
     */
    public function setAllergies($allergies)
    {
        $this->allergies = $allergies;
    }

    /**
     * @return string
     */
    public function getDrugIntolerance()
    {
        return $this->drugIntolerance;
    }

    /**
     * @param string $drugIntolerance
     */
    public function setDrugIntolerance($drugIntolerance)
    {
        $this->drugIntolerance = $drugIntolerance;
    }

    /**
     * @return string
     */
    public function getSmokingStatus()
    {
        return $this->smokingStatus;
    }

    /**
     * @param string $smokingStatus
     */
    public function setSmokingStatus($smokingStatus)
    {
        $this->smokingStatus = $smokingStatus;
    }

    /**
     * @return boolean
     */
    public function isIsPregnant()
    {
        return $this->isPregnant;
    }

    /**
     * @param boolean $isPregnant
     */
    public function setIsPregnant($isPregnant)
    {
        $this->isPregnant = $isPregnant;
    }

    /**
     * @return boolean
     */
    public function isIsBreastFeeding()
    {
        return $this->isBreastFeeding;
    }

    /**
     * @param boolean $isBreastFeeding
     */
    public function setIsBreastFeeding($isBreastFeeding)
    {
        $this->isBreastFeeding = $isBreastFeeding;
    }

    /**
     * @return string
     */
    public function getEmergencycontactname1()
    {
        return $this->emergencycontactname1;
    }

    /**
     * @param string $emergencycontactname1
     */
    public function setEmergencycontactname1($emergencycontactname1)
    {
        $this->emergencycontactname1 = $emergencycontactname1;
    }

    /**
     * @return string
     */
    public function getEmergencycontactphone1()
    {
        return $this->emergencycontactphone1;
    }

    /**
     * @param string $emergencycontactphone1
     */
    public function setEmergencycontactphone1($emergencycontactphone1)
    {
        $this->emergencycontactphone1 = $emergencycontactphone1;
    }


    /**
     * @return string
     */
    public function getNotes()
    {
        return $this->notes;
    }

    /**
     * @param string $notes
     */
    public function setNotes($notes)
    {
        $this->notes = $notes;
    }

    /**
     * @return string
     */
    public function getReferredby()
    {
        return $this->referredby;
    }

    /**
     * @param string $referredby
     */
    public function setReferredby($referredby)
    {
        $this->referredby = $referredby;
    }

    /**
     * @return string
     */
    public function getDiagnosis()
    {
        return $this->diagnosis;
    }

    /**
     * @param string $diagnosis
     */
    public function setDiagnosis($diagnosis)
    {
        $this->diagnosis = $diagnosis;
    }

    /**
     * @return \DateTime
     */
    public function getCreatedon()
    {
        return $this->createdon;
    }

    /**
     * @ORM\PrePersist
     */
    public function setCreatedon()
    {
        $this->createdon = new \DateTime("now");
    }

    /**
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @param string $username
     */
    public function setUsername($username)
    {
        $this->username = $username;
    }

    /**
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password = $password;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param string $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * @return string
     */
    public function getIdentificationtype()
    {
        return $this->identificationtype;
    }

    /**
     * @param string $identificationtype
     */
    public function setIdentificationtype($identificationtype)
    {
        $this->identificationtype = $identificationtype;
    }

    /**
     * @return string
     */
    public function getIdentificationnumber()
    {
        return $this->identificationnumber;
    }

    /**
     * @param string $identificationnumber
     */
    public function setIdentificationnumber($identificationnumber)
    {
        $this->identificationnumber = $identificationnumber;
    }

    /**
     * @return int
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @ORM\PrePersist
     */
    public function setStatus($status)
    {
        $this->status = 1;
    }


    /**
     * @return string
     */
    public function getReminderLanguage()
    {
        return $this->reminderLanguage;
    }

    /**
     * @param string $reminderLanguage
     */
    public function setReminderLanguage($reminderLanguage)
    {
        $this->reminderLanguage = $reminderLanguage;
    }

    /**
     * @return boolean
     */
    public function isMedicalHistoryConsent()
    {
        return $this->medicalHistoryConsent;
    }

    /**
     * @param boolean $medicalHistoryConsent
     */
    public function setMedicalHistoryConsent($medicalHistoryConsent)
    {
        $this->medicalHistoryConsent = $medicalHistoryConsent;
    }

    /**
     * @return string
     */
    public function getPreferedLanguage()
    {
        return $this->preferedLanguage;
    }

    /**
     * @param string $preferedLanguage
     */
    public function setPreferedLanguage($preferedLanguage)
    {
        $this->preferedLanguage = $preferedLanguage;
    }

    /**
     * @return string
     */
    public function getMaritalStatus()
    {
        return $this->maritalStatus;
    }

    /**
     * @param string $maritalStatus
     */
    public function setMaritalStatus($maritalStatus)
    {
        $this->maritalStatus = $maritalStatus;
    }

    /**
     * @return string
     */
    public function getInsuredPersonName()
    {
        return $this->insuredPersonName;
    }

    /**
     * @param string $insuredPersonName
     */
    public function setInsuredPersonName($insuredPersonName)
    {
        $this->insuredPersonName = $insuredPersonName;
    }

    /**
     * @return string
     */
    public function getInsuredPersonPhone()
    {
        return $this->insuredPersonPhone;
    }

    /**
     * @param string $insuredPersonPhone
     */
    public function setInsuredPersonPhone($insuredPersonPhone)
    {
        $this->insuredPersonPhone = $insuredPersonPhone;
    }

    /**
     * @return string
     */
    public function getInsuredPersonIdentificationType()
    {
        return $this->insuredPersonIdentificationType;
    }

    /**
     * @param string $insuredPersonIdentificationType
     */
    public function setInsuredPersonIdentificationType($insuredPersonIdentificationType)
    {
        $this->insuredPersonIdentificationType = $insuredPersonIdentificationType;
    }

    /**
     * @return string
     */
    public function getInsuredPersonIdentificationNumber()
    {
        return $this->insuredPersonIdentificationNumber;
    }

    /**
     * @param string $insuredPersonIdentificationNumber
     */
    public function setInsuredPersonIdentificationNumber($insuredPersonIdentificationNumber)
    {
        $this->insuredPersonIdentificationNumber = $insuredPersonIdentificationNumber;
    }

    /**
     * @return int
     */
    public function getInsuranceCompanyId()
    {
        return $this->insuranceCompanyId;
    }

    /**
     * @param int $insuranceCompanyId
     */
    public function setInsuranceCompanyId($insuranceCompanyId)
    {
        $this->insuranceCompanyId = $insuranceCompanyId;
    }

    /**
     * @return string
     */
    public function getInsurancePolicyNumber()
    {
        return $this->insurancePolicyNumber;
    }

    /**
     * @param string $insurancePolicyNumber
     */
    public function setInsurancePolicyNumber($insurancePolicyNumber)
    {
        $this->insurancePolicyNumber = $insurancePolicyNumber;
    }

    /**
     * @return int
     */
    public function getDoneBy()
    {
        return $this->doneBy;
    }

    /**
     * @param int $doneBy
     */
    public function setDoneBy($doneBy)
    {
        $this->doneBy = $doneBy;
    }

    /**
     * @return int
     */
    public function getRating()
    {
        return $this->rating;
    }

    /**
     * @param int $rating
     */
    public function setRating($rating)
    {
        $this->rating = $rating;
    }

    /**
     * @return string
     */
    public function getProfession()
    {
        return $this->profession;
    }

    /**
     * @param string $profession
     */
    public function setProfession($profession)
    {
        $this->profession = $profession;
    }




}
