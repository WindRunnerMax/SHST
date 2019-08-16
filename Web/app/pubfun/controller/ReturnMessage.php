<?php
namespace app\pubfun\controller;

class ReturnMessage
{
    public static function text($msg,$content){
        return "<xml>
                  <ToUserName><![CDATA[".$msg->FromUserName."]]></ToUserName>
                  <FromUserName><![CDATA[".$msg->ToUserName."]]></FromUserName>
                  <CreateTime>".$msg->CreateTime."</CreateTime>
                  <MsgType><![CDATA[text]]></MsgType>
                  <Content><![CDATA[".$content."]]></Content>
                </xml>";
    }

    public static function imgText($msg,$title,$content,$imgUrl){
        return "<xml>
                  <ToUserName><![CDATA[".$msg->FromUserName."]]></ToUserName>
                  <FromUserName><![CDATA[".$msg->ToUserName."]]></FromUserName>
                  <CreateTime>".$msg->CreateTime."</CreateTime>
                  <MsgType><![CDATA[news]]></MsgType>
                  <ArticleCount>1</ArticleCount>
                  <Articles>
                    <item>
                      <Title><![CDATA[".$title."]]></Title>
                      <Description><![CDATA[".$content."]]></Description>
                      <PicUrl><![CDATA[".$imgUrl."]]></PicUrl>
                      <Url><![CDATA[".$imgUrl."]]></Url>
                    </item>
                  </Articles>
                </xml>";
    }

    public static function img($msg,$id){
        return "<xml>
                  <ToUserName><![CDATA[".$msg->FromUserName."]]></ToUserName>
                  <FromUserName><![CDATA[".$msg->ToUserName."]]></FromUserName>
                  <CreateTime>".$msg->CreateTime."</CreateTime>
                  <MsgType><![CDATA[image]]></MsgType>
                      <Image>
                        <MediaId><![CDATA[".$id."]]></MediaId>
                      </Image>
                </xml>";
    }
}
