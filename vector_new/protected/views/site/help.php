<h1>User guide</h1>
<ol>
	<li><a href='#1'>Project Naming Convention</a></li>
	<li><a href='#2'>Vector Project Definition</a></li>
	<li><a href='#3'>Component and Equipment</a></li>
	<li><a href='#4'>Example of Component/Equipment</a></li>
	<li><a href='#5'>Travelers</a></li>
	<li><a href='#6'>How to use Vector (Role: Creator)</a></li>
	<li><a href='#7'>Attached Equipments</a></li>
</ol>
</br>
<h2 id='1'>Project Naming Convention</h2>
<ul>· In the case of the 11T Dipole:
	<ul>
	<li>- was defined before VECTOR was imported at CERN</li>
	<li>- uses the LHC naming convention</li>
	<li>- Was defined in agreement with EDMS/MTF working group</li>
	</ul>

<li>· MBHSP : Magnet Bending, High Field, Single Aperture Prototype</li>
<li>· MBHDP : Magnet Bending, High Field, Double Aperture Prototype</li>
</ul>
</br>

<h2 id='2'>Vector Project Definition</h2>
<ul>
<li>· VECTOR at CERN is a variant of VECTOR FERMILAB</li>
<li>· CERN VECTOR aims at keeping a certain coherence with LHC naming conventions and MTF structures:</li>
	<ul>
		<li>- Uses naming convention to identify an equipment</li>
		<li>- Uses a project name as “root” for all related equipment</li>
		<li>- Regroups applicable travelers by component type</li>
	</li></ul>
</ul>
</br>

<h2 id='3'>Component and Equipment</h2>
<ul>
<li>· A component is a family regrouping all equipment of the same type, defining:</li>
	<ul>
		<li>- similar hardware elements (i.e. magnet coils)</li>
		<li>- A series of applicable travellers for these elements</li>
	</ul>
<li>· An equipment is a unique piece of component, identified by the component ID and a serial number ( component ID_Serial#)</li>
<li>· An equipment can be subjected to all or part of the travellers defined for a component</li>
</ul>
</br>

<h2 id='4'>Example of Component/Equipment</h2>
<table>
	<tr>
		<th>Component</th>
		<th>Equipment</th>
	</tr>
	<tr>
		<td>Defines a family of elements</td>
		<td>Identifies a unique element</td>
	</tr>
	<tr>
		<td>Is equivalent in industry to the Product Number (P/N)
</td>
		<td>Is composed of the component name + a serial number
</td>
	</tr>
	<tr>
		<td>Uses MTF compatible naming</td>
		<td>Is equivalent in industry to the Serial Number (S/N)</td>
	</tr>
	<tr>
		<td>Ex. : HCMBHSP0003   
</br>-> identifies all the coils for the 11T Dipole</td>
		<td>Ex.: HCMBHSP0003_101
</br>-> identifies the coil number 101 of the type 11T Dipole</td>
	</tr>
</table>
</br>

<h2 id='5'>Travelers</h2>
<ul>
<li>· Caution: the term “Traveler” is ambiguous. It defines both the “Template of a traveler” and an issue or release of this traveler template.</h3></li>

<li>· The Traveler Template is an empty form containing text and graphical elements, as well as fields to be filled in by the operator. <u>The template is associated with a component</u> , and defines a set of steps to follow to achieve an action related to the production of an equipment.</li>

<li>· <u>An issue of traveler, is a copy of the template that has been associated to a unique piece of equipment and filled in with the data collected during the work on this equipment.</u> There can be only one issue per equipment.</li>

<li>· Traveler template can evolve (versioning). Only the latest version of a traveler template can be used with a new equipment. Old equipments already produced are associated with the version that was used during their production</li>
</ul>
</br>

<h2 id='6'>How to use Vector (Role: Creator)</h2>
<ol>
	<li>First of all, work out a PBS (product breakdown structure) compatible with MTF
		Nota: in VECTOR the PBS should mainly be related to physical deliverables (hardware items), at least for the first stage of development of the CERN VECTOR System</li>
	<li>Log in as “Creator” in VECTOR CERN</li>
	<li>In “Projects” Tab of CERN VECTOR, create your project (i.e. MBH)</li>
	<li>In “Projects” Tab of CERN VECTOR, select your project, then create your various components according to your PBS</li>
		<ul>
			<li><u>Nota; in VECTOR the breakdown structure is not existing. VECTOR lists the components as you enter them, without hierarchic link between them</u></li>
		</ul>
	<li>In “Traveler” Tab of CERN VECTOR, create a new traveler. Associate it to a component, and draft a first version (v0) of the expected set of procedures . Test and Publish</li>
	<li>In ”Equipments” Tab of CERN VECTOR, create the first equipment of a component</li>

</ol>
</br>
</br>
<div  style='text-align: center; display:block'>
<img src='../../images/equipmentsGuide.png' style='width:100%; max-width:none;'>
</div>
</br>


<h2 id='7'>Attached Equipments</h2>
<ul>
	<li>· VECTOR sofware is not associating a hierarchic structure with the PBS.
</li>
	<li>· It is however possible to “attach” equipments to other to create “children” dependency between equipments. 
</li>
	<li>· For example:</li>
		<ul>
			<li>- a cable should be attached to a coil equipment</li>
			<li>- 2 coils should be attached to a collared coils equipment</li>
			<li>- A collared coil should be attached to a magnet</li>
		</ul>
	<li>· CAUTION: When working with “attached equipment”: detaching make you lose the information about the parent-child links (the system will no longer retrieve which sub-assembly was previously attached with your equipment). In such case, before detaching, record, inside your Top-Equipment all sub-equipments that were associated with it and are no longer attached (a special “disassembly traveler” would seem appropriate for this case)</li>
</ul>




