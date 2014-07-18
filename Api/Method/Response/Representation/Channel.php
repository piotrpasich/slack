<?php

/*
 * This file is part of the CLSlackBundle.
 *
 * (c) Cas Leentfaar <info@casleentfaar.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace CL\Slack\Api\Method\Response\Representation;

use CL\Slack\Resolvable;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * @author Cas Leentfaar <info@casleentfaar.com>
 */
class Channel
{
    use Resolvable;

    /**
     * @var array
     */
    protected $data;

    /**
     * @param array $data
     */
    public function __construct(array $data)
    {
        $this->data = $this->resolve($data);
    }

    /**
     * @return string The ID of this channel.
     */
    public function getId()
    {
        return $this->data['id'];
    }

    /**
     * @return string The name of the channel, without a leading hash sign.
     */
    public function getName()
    {
        return $this->data['name'];
    }

    /**
     * @return Message The latest message in the channel.
     */
    public function getLatestMessage()
    {
        return $this->data['latest'];
    }

    /**
     * @return string The Slack timestamp for the last message the calling user has read in this channel.
     */
    public function getLastRead()
    {
        return $this->data['last_read'];
    }

    /**
     * @return \DateTime The date/time on which this channel was created.
     */
    public function getCreated()
    {
        return $this->data['created'];
    }

    /**
     * @return string The user ID of the member that created this channel.
     */
    public function getCreator()
    {
        return $this->data['creator'];
    }

    /**
     * @return bool True if this channel has been archived, false otherwise.
     */
    public function isArchived()
    {
        return $this->data['is_archived'];
    }

    /**
     * @return bool Returns true if this channel is the "general" channel that includes all regular team members,
     *              false otherwise. In most teams this is called #general but some teams have renamed it.
     */
    public function isGeneral()
    {
        return $this->data['is_general'];
    }

    /**
     * @return array A list of user ids for all users in this channel.
     *               This includes any disabled accounts that were in this channel when they were disabled.
     */
    public function getMembers()
    {
        return $this->data['members'];
    }

    /**
     * @return array Information about the channel's topic.
     */
    public function getTopic()
    {
        return $this->data['topic'];
    }

    /**
     * @return array Information about the channel's purpose.
     */
    public function getPurpose()
    {
        return $this->data['purpose'];
    }

    /**
     * {@inheritdoc}
     */
    protected function configureResolver(OptionsResolverInterface $resolver)
    {
        $resolver->setRequired([
            'id',
            'name',
            'created',
            'creator',
            'is_archived',
            'is_general',
            'members',
            'is_member',
            'last_read',
            'latest',
            'unread_count',
            'topic',
            'purpose',
        ]);
        $resolver->setAllowedTypes([
            'id'           => ['string'],
            'name'         => ['string'],
            'created'      => ['\DateTime'],
            'creator'      => ['string'],
            'is_archived'  => ['bool'],
            'is_general'   => ['bool'],
            'members'      => ['array'],
            'is_member'    => ['bool'],
            'last_read'    => ['string'],
            'latest'       => ['\CL\Slack\Api\Method\Response\Representation\Message'],
            'unread_count' => ['int'],
            'topic'        => ['array'],
            'purpose'      => ['array'],
        ]);
        $resolver->setNormalizers([
            'created' => function (Options $options, $created) {
                return new \DateTime($created);
            },
            'latest' => function (Options $options, array $latestMessage) {
                return new Message($latestMessage);
            },
        ]);

        return $resolver;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return $this->data;
    }
}