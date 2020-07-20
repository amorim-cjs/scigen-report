
<body>

<div class="w3-container" style="max-width:1000px;padding-bottom:50px;">
<h2>What can I submit? </h2>

<p>The short answer is anything regarding the <i>reproducibility</i> of a published paper. Naturally, this begs the question: what <i>is</i> <b>reproducibility</b>? It is hard to give a short answer to this question, for several definitions are available and they may change depending on the field. As we believe in the power of the scientific community, we do not want to restrict submissions based on a single definition. Instead, we shall try to walk you through some perspectives available that can guide your decision on whether some material is adequate to this website or not, finishing up with a summary and our view on this matter. Every definition we provide here are within our scope of valid submissions.</p>

<h2>Reproducing or replicating?</h2>

<p>Reproduction and replication of a study are often used as synonyms, but is there a difference between <i>reproducing</i> and <i>replicating</i> an analysis? We assume a broad range of types of research paper, so it could be a statistical analysis, a theoretical model, an experiment, a numerical algorithm, and so on. This broad spectrum of research becomes the main source of divergence in understanding. Some fields seem to use these words interchangeably with the same meaning, while other fields make a difference, which can be reversed between one field and another (Plesser, 2018). Here, we will assume that these two words do mean different things according to context, usually one meaning that the whole study setup is the same, and the other implying a different setup (e.g., samples, cohorts, environmental conditions, etc.).</p>

<h2>The Turing Way Definition</h2>

<p>The Turing Way is an open book (The Turing Way Community, 2019) that provides the following 4-fold definition, which one may use as personal guidance: <i>reproducible, replicable, robust, generalizable</i>. Each of these words is defined based on whether the repetition of the study in question employs the same analysis (or not) to the same dataset (or not). It leads to the following table.<p>


<div>
  <div class="w3-center">
  <img style="max-height:300px;margin:auto;" src="https://scigen.report/assets/images/TuringRep.png">
  <p>Table 1: The Turing Way definition (The Turing Way Community, 2019)</p>
  </div>
</div>

<p>In this definition, “same” has a quite strict assessment bar. For instance, given any analysis done in a computer, the same code and language must run on the same dataset for it to be a <i>reproduction</i> of the original study, while a code written by another person (possibly in another language) would lead to a <i>robust</i> result (or not), even if following the same algorithm but not the same code (e.g., depending on other libraries, component version, or written in a different language). Similarly, small changes in the analyzed data would already imply a replication, which virtually makes “reproduction” impossible in some fields, only replication may be achievable. Note that this is not a problem per se, since it is only a matter of naming definition.</p>

<h2>An Expanded Definition</h2>

<p>In SciGen.Report, all of the conditions listed in The Turing Way are valid targets to be evaluated and shared with the community. However, we aim for an even more ambitious range, including also partial analyses and partial data. By “partial” we mean slightly different things, that may fall in the following categories for data:</p>

<ul>
<li>
  <i>Restricted same data</i>: only a portion of the same original dataset is used, not in its entirety. Since not all data is used, but the same data is still targeted, the analysis may point to the same direction as the whole data. In this case, we shall say the study has an <i>asymptotic reproducibility</i> or <i>asymptotic robustness</i>.
</li>
<li>
    <i>Partially/whole different data</i>: part of/all the data is different from the original. Addition or substitution of some data makes a partially different data. Even a partially different dataset implies different data in The Turing Way. To emphasize the case where the whole dataset is different, we shall call the observed replicability/generalizability <i>extensive</i>.
</li>
</ul>

<p>Regarding analyses, one must first suppose that a study may conduct a single analysis that cannot be broken into pieces, or a more complex or convoluted analysis that may be composed by smaller procedures. We may call the former <i>atomic analysis</i>, as it cannot be divided further. Any analysis that is not atomic may therefore be somehow split into partial analysis. By definition, atomic analysis cannot be partially conducted. For an analysis that is not atomic, we can conceive the following partial analysis pattern:</p>

<ul>
<li>
  <i>Selected same analysis</i>: only some subset of analyses is conducted. Therefore, the property discussed can be labeled <i>selective</i>.
</li>
<li>
<i>partially/whole different analysis</i>: some/all pieces of the analysis are modified from the original. Because this offers a degree of analysis difference, one can emphasize such degree by denoting the result as <i>weak/strong</i>.
</li>
</ul>

<p>Note that data can be partially the same and partially different at the same time (say, half the same, and half different). Strictly speaking, in such cases, the difference “absorbs” the classification, leaving the data simply “partially different.” The same can be said about the analysis: if only some procedures are selected, and part of these are different, it is a partially different analysis. Hence, a revised version of The Turing Way definition can be summarized in Table 2.</p>


<div>
  <div class="w3-center">
  <img class="w3-center" style="max-height:450px;margin:auto;" src="https://scigen.report/assets/images/reproducibility.png">
 <p>Table 2: Expansion of The Turing Way definitions.</p>
  </div>
</div>



<p>In SciGen.Report, we want to address all this trials, no matter whether they are positive or negative results.</p>


<h3>References:</h3>

<ul>
<li>
Plesser, Hans E. 2018. <i>Frontier in Neuroinformatics</i>. Vol. 11, Article 76. DOI: 10.3389/fninf.2017.00076.
</li>
<li>
The Turing Way Community, Becky Arnold, Louise Bowler, Sarah Gibson, Patricia Herterich, Rosie Higman, … Kirstie Whitaker. 2019. <i>The Turing Way: A Handbook for Reproducible Data Science</i> (Version v0.0.4). Zenodo. DOI:10.5281/zenodo.3233986
</li>
</ul>

</div>

</body>
