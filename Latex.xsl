<xsl:stylesheet version="3.0"
                xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
<xsl:output method="text" encoding="utf-8"/>

<xsl:template match="/">
    <xsl:text>\documentclass[12pt]{article}</xsl:text>
    <xsl:text>\usepackage[utf8]{inputenc}</xsl:text>
    <xsl:text>\usepackage{xcolor}</xsl:text>
    <xsl:text>\begin{document}</xsl:text>

    <!-- Boucler sur tous les thèmes possibles en assumant une structure XML flat pour simplifier -->
    <xsl:for-each select="submissions/submission">
        <xsl:sort select="theme"/>
        <!-- Pour chaque soumission, vérifier si c'est la première occurrence de ce thème dans le document -->
        <xsl:if test="not(preceding::submission[theme = current()/theme])">
            <!-- Traitement pour un nouveau thème -->
            <xsl:text>\section*{\textcolor{red}{\Huge Theme: </xsl:text>
            <xsl:value-of select="theme"/>
            <xsl:text>}}</xsl:text>

            <!-- Boucler sur toutes les soumissions de ce thème, triées par titre puis par nom de l'auteur principal -->
            <xsl:for-each select="//submission[theme = current()/theme]">
               <xsl:sort select="title"/>
                <xsl:sort select="firstauthor/lastname"/>
                

                <xsl:text>\begin{center}</xsl:text>
                <xsl:text>\section*{</xsl:text>
                <xsl:value-of select="title"/>
                <xsl:text>}</xsl:text>
                <xsl:text>\end{center}</xsl:text>

                <xsl:text>\begin{center}</xsl:text>
                <xsl:value-of select="firstauthor/lastname"/>
                <xsl:text> </xsl:text>
                <xsl:value-of select="firstauthor/firstname"/>
                <xsl:text>\\</xsl:text>
                <xsl:text>\texttt{</xsl:text>
                <xsl:value-of select="mail[@type='institutional']"/>
                <xsl:text>}</xsl:text>
                <xsl:text>\end{center}</xsl:text>

                <xsl:text>\begin{abstract}</xsl:text>
                <xsl:value-of select="abstract"/>
                <xsl:text>\end{abstract}</xsl:text>

                <xsl:text>\noindent\rule{\textwidth}{1pt}</xsl:text>
            </xsl:for-each>
        </xsl:if>
    </xsl:for-each>
    <xsl:text>\end{document}</xsl:text>
</xsl:template>

</xsl:stylesheet>
