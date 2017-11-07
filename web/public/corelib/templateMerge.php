<?php
/**
 * Legacy class.
 *
 * Integrates calls to the old template system to the plates template system.
 */

class templateMerge {
    var $fields;
    var $dataNames;
    var $pageData;

    function __construct($templateFile) {
        $this->pageData = array();
        $src = file_get_contents($templateFile);
        $tmpfields = explode("<%", $src);
        $this->fields = array();
        $this->fields[] = "echo " . $tmpfields[0];
        for ($i = 1; $i < count($tmpfields); $i++) {// start FOR loop
            $this->fields[] = substr($tmpfields[$i], 0, strpos($tmpfields[$i], "%>"));
            $this->fields[] = "echo " . substr($tmpfields[$i], strpos($tmpfields[$i], "%>") + 2);
        }// end FOR loop

        for ($i = 0; $i < count($this->fields); $i++) {// start FOR loop

            if (strpos($this->fields[$i], " ") !== false) {
                list($type, $fieldinfo) = explode(" ", $this->fields[$i], 2);
            } else {
                $type = $this->fields[$i];
                $fieldinfo = '';
            }

            if ($type != 'echo') {
                $params = $this->decodeParams($fieldinfo);
                if (array_key_exists('name', $params)) {
                    $dataname = $params['name'];
                    $this->dataNames[] = $dataname;
                }
            }

        }// end FOR loop
    }// end templateMerge()

    function render() {// start render()
        global $CFG;

        $templates = new League\Plates\Engine($CFG['templates']);

        // Data to pass to template
        $data = array();

        // Foreach page field
        for($i = 0; $i < count($this->fields); $i++) {

            // Decode field
            if (strpos($this->fields[$i], ' ') === false) {
                continue;
            }

            list($type, $fieldinfo) = explode(' ', $this->fields[$i], 2);

            // If this is a section
            if($type == "section") {

                // Get section name and value
                $name = $this->decodeParams($fieldinfo)["name"];
                $value = $this->pageData[$this->decodeParams($fieldinfo)["name"]];

                $data[$name] = $value;
            }
        }
        //print_r($data);
        echo $templates->render("legacy", $data);
    }


    function decodeParams($data) {
        $params = array();
        // eg name="BannerAds_468x60_as" div_id="bannerAds"
        // or  name="Ads_728x90_as" <#start> <div id="horizontalAds"> <#content> </div> <#end>

        // first find if there's a <#start> <#content> <#end> section
        if (strpos($data, '<#start>') !== false) {
            list($data, $merge) = explode('<#start>', $data, 2);
            $params['merge'] = '<#start>' . $merge;
        }
        $data = trim($data);
        while (strlen($data) > 0) {// start WHILE loop
            $sp = strcspn($data, "=\"'\r\n\t ");

            if ($sp > 0) {
                $name = substr($data, 0, $sp);
                $data = trim(substr($data, $sp));

                if (substr($data, 0, 1) == '=') {
                    $data = trim(substr($data, 1));
                    // if " or ' find match
                    $delim = substr($data, 0, 1);

                    if ($delim == '"' || $delim == "'") {
                        $ep = strpos($data, $delim, 1);

                        if ($ep !== false) {
                            $params[$name] = substr($data, 1, $ep - 1);
                            $data = trim(substr($data, $ep + 1));
                        } else {
                            $data = '';// malformed. give up
                        }// end if ($ep!==false)

                    } else {// find next space
                        $ep = strcspn($data, "\r\n\t ");

                        if ($ep !== false) {
                            $params[$name] = substr($data, 0, $ep);
                            $data = trim(substr($data, $ep + 1));
                        } else {
                            $params[$name] = $data;
                            $data = '';
                        }// end if ($ep!==false)

                    }// end if ($delim=='"' || $delim=="'")

                } else {
                    $params[$name] = true;
                }// end if (substr($data, 0, 1)=='=')

            } else {
                $data = '';// malformed. giveup
            }// end if ($sp>0)

        }// end WHILE loop

        return $params;
    }// end decodeParams()

}// end class
?>
